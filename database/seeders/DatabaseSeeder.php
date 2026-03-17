<?php

namespace Database\Seeders;

use App\Models\{User, Wallet};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name'  => 'TGether',
            'email'      => 'admin@tgether.cm',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'is_verified'=> true,
            'is_active'  => true,
        ]);
        Wallet::create(['user_id' => $admin->id, 'balance' => 0, 'currency' => 'XAF']);

        // Demo driver
        $driver = User::create([
            'first_name' => 'Jean',
            'last_name'  => 'Conducteur',
            'email'      => 'driver@tgether.cm',
            'password'   => Hash::make('password'),
            'role'       => 'driver',
            'is_verified'=> true,
            'is_active'  => true,
        ]);
        Wallet::create(['user_id' => $driver->id, 'balance' => 15000, 'currency' => 'XAF']);

        // Demo passenger
        $passenger = User::create([
            'first_name' => 'Marie',
            'last_name'  => 'Passagère',
            'email'      => 'passenger@tgether.cm',
            'password'   => Hash::make('password'),
            'role'       => 'passenger',
            'is_verified'=> true,
            'is_active'  => true,
        ]);
        Wallet::create(['user_id' => $passenger->id, 'balance' => 25000, 'currency' => 'XAF']);

        $this->command->info('✅ Comptes de démonstration créés:');
        $this->command->table(['Rôle','Email','Mot de passe'], [
            ['Admin',     'admin@tgether.cm',     'password'],
            ['Conducteur','driver@tgether.cm',    'password'],
            ['Passager',  'passenger@tgether.cm', 'password'],
        ]);
    }
}
