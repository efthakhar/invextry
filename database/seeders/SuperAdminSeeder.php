<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // create super admin
        $super_admin_user = User::create([
            'name' => 'super admin',
            'user_name' => 'superadmin',
            'email' => 'superadmin@invextry.com',
            'password' => Hash::make('**invextry**'),
        ]);

        // assing this super admin to super-admin role
        $super_admin_user->assignRole('super-admin');

        // make super admin logged in
        Auth::attempt([
            'email' => 'superadmin@invextry.com',
            'password' => '**invextry**',
        ], 100);
    }
}

// email: superadmin@invextry.com
// password: **invextry**
