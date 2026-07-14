<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    protected array $customers = [
        ['name' => 'Budi Santoso',              'phone' => '081234567801', 'points' => 250],
        ['name' => 'Siti Rahmawati',            'phone' => '081234567802', 'points' => 180],
        ['name' => 'Ahmad Hidayat',             'phone' => '081234567803', 'points' => 30],
        ['name' => 'Dewi Lestari',              'phone' => '081234567804', 'points' => 0],
        ['name' => 'Rudi Hermawan',             'phone' => '081234567805', 'points' => 520],
        ['name' => 'Nurul Aini',                'phone' => '081234567806', 'points' => 75],
        ['name' => 'Hendra Gunawan',            'phone' => '081234567807', 'points' => 12],
        ['name' => 'Fitri Handayani',           'phone' => '081234567808', 'points' => 0],
        ['name' => 'Agus Prasetyo',             'phone' => '081234567809', 'points' => 1100],
        ['name' => 'Rina Marlina',              'phone' => '081234567810', 'points' => 44],
    ];

    public function run(): void
    {
        foreach ($this->customers as $customer) {
            Customer::firstOrCreate(
                ['phone' => $customer['phone']],
                $customer
            );
        }
    }
}
