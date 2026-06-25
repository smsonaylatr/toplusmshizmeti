<?php

namespace App\Livewire;

use App\Models\BlacklistedNumber;
use Livewire\Component;

class Blacklist extends Component
{
    public string $numbersInput = '';

    public function blockNumbers()
    {
        $numbers = $this->parseNumbers($this->numbersInput);

        if (empty($numbers)) {
            return;
        }

        foreach ($numbers as $number) {
            BlacklistedNumber::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'phone_number' => $number,
                ],
                [
                    'source' => 'manual',
                ]
            );
        }

        $this->numbersInput = '';
    }

    public function unblockNumbers()
    {
        $numbers = $this->parseNumbers($this->numbersInput);

        if (empty($numbers)) {
            return;
        }

        BlacklistedNumber::where('user_id', auth()->id())
            ->whereIn('phone_number', $numbers)
            ->delete();

        $this->numbersInput = '';
    }

    public function removeNumber($id)
    {
        BlacklistedNumber::where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();
    }

    private function parseNumbers(string $input): array
    {
        $lines = preg_split('/[\n,;]+/', $input);
        $numbers = [];

        foreach ($lines as $line) {
            $clean = preg_replace('/\D/', '', trim($line));
            if (strlen($clean) >= 10) {
                $numbers[] = $clean;
            }
        }

        return array_unique($numbers);
    }

    public function render()
    {
        $blockedNumbers = BlacklistedNumber::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('livewire.blacklist', [
            'blockedNumbers' => $blockedNumbers,
        ])->layout('components.layouts.panel', ['title' => 'Kara Liste']);
    }
}
