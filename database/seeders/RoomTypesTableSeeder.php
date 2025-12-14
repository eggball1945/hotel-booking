<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'Standard Single',
                'description' => 'Kamar standar untuk 1 orang dengan fasilitas dasar',
                'price' => 300000,
                'photo' => null,
            ],
            [
                'name' => 'Standard Double',
                'description' => 'Kamar standar untuk 2 orang dengan fasilitas dasar',
                'price' => 500000,
                'photo' => null,
            ],
            [
                'name' => 'Standard Twin',
                'description' => 'Kamar dengan 2 tempat tidur single untuk 2 orang',
                'price' => 550000,
                'photo' => null,
            ],
            [
                'name' => 'Deluxe Double',
                'description' => 'Kamar mewah untuk 2 orang dengan fasilitas lengkap',
                'price' => 750000,
                'photo' => null,
            ],
            [
                'name' => 'Deluxe Twin',
                'description' => 'Kamar mewah dengan 2 tempat tidur single',
                'price' => 800000,
                'photo' => null,
            ],
            [
                'name' => 'Family Room',
                'description' => 'Kamar luas untuk keluarga dengan 4 tempat tidur',
                'price' => 1000000,
                'photo' => null,
            ],
            [
                'name' => 'Junior Suite',
                'description' => 'Suite standar dengan ruang tamu terpisah',
                'price' => 1200000,
                'photo' => null,
            ],
            [
                'name' => 'Executive Suite',
                'description' => 'Suite eksekutif dengan fasilitas premium',
                'price' => 1500000,
                'photo' => null,
            ],
            [
                'name' => 'Presidential Suite',
                'description' => 'Suite terbaik dengan semua fasilitas mewah',
                'price' => 2500000,
                'photo' => null,
            ],
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::create($roomType);
        }
    }
}