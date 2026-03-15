<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Alice — default preferences (subscribed to everything, instant frequency)
        $alice = User::firstOrCreate(['email' => 'alice@example.com'], [
            'name'     => 'Alice',
            'password' => Hash::make('password'),
        ]);

        // Bob — unsubscribed from marketing, digest on daily
        $bob = User::firstOrCreate(['email' => 'bob@example.com'], [
            'name'     => 'Bob',
            'password' => Hash::make('password'),
        ]);
        $bob->subscribe('marketing');
        $bob->unsubscribe('marketing');
        $bob->subscribe('digest');
        $bob->setEmailFrequency('digest', 'daily');

        // Carol — digest on weekly
        $carol = User::firstOrCreate(['email' => 'carol@example.com'], [
            'name'     => 'Carol',
            'password' => Hash::make('password'),
        ]);
        $carol->subscribe('digest');
        $carol->setEmailFrequency('digest', 'weekly');
        $carol->subscribe('marketing');
    }
}
