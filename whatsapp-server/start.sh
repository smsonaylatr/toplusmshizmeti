#!/bin/bash

# WhatsApp Node.js Sunucusunu başlatma betiği
# Bu betik sunucunun çalışıp çalışmadığını kontrol eder, çalışmıyorsa başlatır.

cd "$(dirname "$0")"

# node komutu sunucuda tam yolu gerektiriyorsa (Örn: Plesk), burayı değiştirebilirsiniz.
NODE_BIN="node"

if pgrep -f "$NODE_BIN index.js" > /dev/null
then
    echo "WhatsApp sunucusu zaten çalışıyor."
else
    echo "WhatsApp sunucusu başlatılıyor..."
    nohup $NODE_BIN index.js > server.log 2>&1 &
    echo "Sunucu başlatıldı! Loglar server.log dosyasına yazılacak."
fi
