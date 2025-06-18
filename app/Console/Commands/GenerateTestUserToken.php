<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class GenerateTestUserToken extends Command
{
    protected $signature = 'test:token';
    protected $description = 'Generate a Sanctum token for demo user';

    public function handle(): int
    {
        $user = User::updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
            ],
        );

        $token = $user->createToken('test-api-token')->plainTextToken;

        $this->info("✅ User created/updated successfully!");
        $this->line("Email: demo@example.com");
        $this->line("Password: password");
        $this->info("🔐 Token:\nBearer $token");

        return 0;
    }
}
