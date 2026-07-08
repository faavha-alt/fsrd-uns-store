<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BuyerSeeder extends Seeder
{
    public function run(): void
    {
        $buyers = [
            [
                'name'     => 'Ahmad Fauzi',
                'email'    => 'ahmad.fauzi@gmail.com',
                'phone'    => '081234567890',
                'password' => 'password123',
            ],
            [
                'name'     => 'Dewi Rahayu',
                'email'    => 'dewi.rahayu@gmail.com',
                'phone'    => '082345678901',
                'password' => 'password123',
            ],
            [
                'name'     => 'Bima Sakti',
                'email'    => 'bima.sakti@gmail.com',
                'phone'    => '083456789012',
                'password' => 'password123',
            ],
        ];

        foreach ($buyers as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'      => $data['name'],
                    'phone'     => $data['phone'],
                    'password'  => Hash::make($data['password']),
                    'role'      => UserRole::Buyer,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✅ Akun buyer berhasil dibuat!');
        $this->command->info('   Login: ahmad.fauzi@gmail.com / password123');
        $this->command->info('   Login: dewi.rahayu@gmail.com / password123');
        $this->command->info('   Login: bima.sakti@gmail.com / password123');
    }
}
