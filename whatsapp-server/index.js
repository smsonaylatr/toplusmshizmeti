const express = require('express');
const { default: makeWASocket, useMultiFileAuthState, DisconnectReason } = require('@whiskeysockets/baileys');
const pino = require('pino');
const cors = require('cors');
const qrcode = require('qrcode');
const fs = require('fs');
const path = require('path');

const app = express();
app.use(express.json());
app.use(cors());

const port = process.env.PORT || 3000;
const sessions = new Map();

function deleteFolderRecursive(directoryPath) {
    if (fs.existsSync(directoryPath)) {
        fs.readdirSync(directoryPath).forEach((file) => {
            const curPath = path.join(directoryPath, file);
            if (fs.lstatSync(curPath).isDirectory()) {
                deleteFolderRecursive(curPath);
            } else {
                fs.unlinkSync(curPath);
            }
        });
        fs.rmdirSync(directoryPath);
    }
}

async function startSession(sessionId) {
    if (sessions.has(sessionId)) {
        return sessions.get(sessionId);
    }

    const sessionDir = path.join(__dirname, 'wsessions', sessionId);
    const { state, saveCreds } = await useMultiFileAuthState(sessionDir);

    const sock = makeWASocket({
        auth: state,
        printQRInTerminal: false,
        logger: pino({ level: 'silent' }),
    });

    let currentQr = null;
    let currentStatus = 'initializing';
    let myNumber = null;

    sock.ev.on('creds.update', saveCreds);

    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;
        
        if (qr) {
            currentQr = qr;
            currentStatus = 'qr_ready';
        }

        if (connection === 'close') {
            currentQr = null;
            const shouldReconnect = lastDisconnect.error?.output?.statusCode !== DisconnectReason.loggedOut;
            currentStatus = shouldReconnect ? 'reconnecting' : 'disconnected';
            
            console.log(`Connection closed for session ${sessionId}. Reconnecting: ${shouldReconnect}`);
            
            if (shouldReconnect) {
                sessions.delete(sessionId);
                startSession(sessionId);
            } else {
                sessions.delete(sessionId);
                deleteFolderRecursive(sessionDir);
            }
        } else if (connection === 'open') {
            console.log(`Opened connection for session ${sessionId}`);
            currentQr = null;
            currentStatus = 'connected';
            myNumber = sock.user.id.split(':')[0];
        }
    });

    const sessionData = {
        sock,
        getQr: () => currentQr,
        getStatus: () => currentStatus,
        getNumber: () => myNumber
    };

    sessions.set(sessionId, sessionData);
    return sessionData;
}

app.post('/session/start', async (req, res) => {
    const { sessionId } = req.body;
    if (!sessionId) return res.status(400).json({ error: 'sessionId is required' });

    try {
        const session = await startSession(sessionId);
        
        let retries = 0;
        while (session.getStatus() === 'initializing' && retries < 15) {
            await new Promise(r => setTimeout(r, 1000));
            retries++;
        }

        const status = session.getStatus();
        if (status === 'qr_ready') {
            const qr = session.getQr();
            if (qr) {
                const qrImage = await qrcode.toDataURL(qr);
                return res.json({ status: 'qr_ready', qr: qrImage });
            }
        }

        if (status === 'connected') {
            return res.json({ status: 'connected', number: session.getNumber() });
        }

        return res.json({ status });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.get('/session/status/:id', async (req, res) => {
    const sessionId = req.params.id;
    const sessionDir = path.join(__dirname, 'wsessions', sessionId);
    
    if (sessions.has(sessionId)) {
        const session = sessions.get(sessionId);
        return res.json({ status: session.getStatus(), number: session.getNumber() });
    }

    if (fs.existsSync(sessionDir)) {
        const session = await startSession(sessionId);
        return res.json({ status: 'initializing' });
    }

    res.json({ status: 'disconnected' });
});

app.post('/session/logout/:id', async (req, res) => {
    const sessionId = req.params.id;
    
    if (sessions.has(sessionId)) {
        const session = sessions.get(sessionId);
        await session.sock.logout();
        sessions.delete(sessionId);
    }
    
    const sessionDir = path.join(__dirname, 'wsessions', sessionId);
    deleteFolderRecursive(sessionDir);
    
    res.json({ success: true, message: 'Logged out successfully' });
});

app.post('/message/send', async (req, res) => {
    const { sessionId, to, message } = req.body;
    
    if (!sessionId || !to || !message) {
        return res.status(400).json({ error: 'sessionId, to, and message are required' });
    }

    if (!sessions.has(sessionId)) {
        return res.status(404).json({ error: 'Session not found or not connected' });
    }

    const session = sessions.get(sessionId);
    if (session.getStatus() !== 'connected') {
        return res.status(400).json({ error: 'Session is not connected' });
    }

    try {
        const sock = sessions.get(sessionId).sock;
        
        // Numara formatlaması: Başında 0 yoksa ve 10 haneliyse (Türkiye) 90 ekle.
        let formattedTo = to.replace(/[^0-9]/g, '');
        if (formattedTo.length === 10 && formattedTo.startsWith('5')) {
            formattedTo = '90' + formattedTo;
        } else if (formattedTo.length === 11 && formattedTo.startsWith('0')) {
            formattedTo = '90' + formattedTo.substring(1);
        }
        const recipient = formattedTo.includes('@') ? formattedTo : `${formattedTo}@s.whatsapp.net`;
        
        console.log(`Sending message to ${recipient}...`);
        const result = await sock.sendMessage(recipient, { text: message });
        console.log(`Message sent successfully: ${JSON.stringify(result)}`);
        
        res.json({ success: true, message: 'Sent' });
    } catch (error) {
        console.error('Send message error:', error);
        res.status(500).json({ error: error.message });
    }
});

app.listen(port, () => {
    console.log(`WhatsApp Baileys server running on http://localhost:${port}`);
    const sessionsDir = path.join(__dirname, 'wsessions');
    if (!fs.existsSync(sessionsDir)) {
        fs.mkdirSync(sessionsDir);
    }
    const dirs = fs.readdirSync(sessionsDir);
    dirs.forEach(dir => {
        if (fs.lstatSync(path.join(sessionsDir, dir)).isDirectory()) {
            console.log(`Auto-starting session: ${dir}`);
            startSession(dir);
        }
    });
});
