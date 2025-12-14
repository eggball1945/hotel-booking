<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run() {
        User::create([
            'name'=>'Admin',
            'email'=>'admin@example.com',
            'password'=>Hash::make('password'),
            'role'=>'admin'
        ]);
        User::create([
            'name'=>'Resepsionis',
            'email'=>'resepsionis@example.com',
            'password'=>Hash::make('password'),
            'role'=>'resepsionis'
        ]);
        User::create([
            'name'=>'Tamu',
            'email'=>'tamu@example.com',
            'password'=>Hash::make('password'),
            'role'=>'tamu'
        ]);
    }
}
