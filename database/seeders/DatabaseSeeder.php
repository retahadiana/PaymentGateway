<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@fastorder.test'],
            [
                'name' => 'Admin Fast Order',
                'type' => 'customer',
                'is_admin' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $vendor = User::firstOrCreate(
            ['email' => 'vendor@fastorder.test'],
            [
                'name' => 'Vendor Demo',
                'type' => 'vendor',
                'id_vendor' => null,
                'phone' => '081234567890',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $vendor->forceFill(['id_vendor' => $vendor->id])->save();

        Vendor::firstOrCreate(
            ['id_vendor' => $vendor->id],
            [
                'nama_vendor' => 'Vendor Demo',
                'email' => 'vendor@fastorder.test',
                'phone' => '081234567890',
                'alamat' => 'Alamat demo vendor',
                'kota' => 'Jakarta',
            ]
        );
    }
}
