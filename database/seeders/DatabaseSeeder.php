<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'ADMIN',
            'email' => 'superadmin@admin.com',
            'email_verified_at' => now(),
            'type_user' => 'SA',
            'phone' => '+520000000000',
            'phoneCode' => '+52',
            'type_doc' => 'CEDULA',
            'password' => Hash::make('admin'),
            'remember_token' => Str::random(10),
        ]);
    }
}
