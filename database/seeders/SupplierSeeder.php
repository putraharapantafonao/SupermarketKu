<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    protected array $suppliers = [
        [
            'name'    => 'PT Indofood Sukses Makmur',
            'phone'   => '081288881001',
            'address' => 'Jl. Pulo Mas Barat XII No.1, Jakarta Timur',
        ],
        [
            'name'    => 'CV Sinar Mulia Trading',
            'phone'   => '081366662002',
            'address' => 'Jl. Pemuda No.45, Lhokseumawe',
        ],
        [
            'name'    => 'PT Sumber Rezeki Distribution',
            'phone'   => '082345678901',
            'address' => 'Jl. T. Hamzah Bendahara No.12, Aceh Utara',
        ],
        [
            'name'    => 'CV Maju Jaya Sentosa',
            'phone'   => '085277773003',
            'address' => 'Jl. Darussalam No.8, Banda Aceh',
        ],
        [
            'name'    => 'PT Mayora Indah Tbk',
            'phone'   => '081999994004',
            'address' => 'Jl. Tomang Raya No.2-4, Jakarta Barat',
        ],
    ];

    public function run(): void
    {
        foreach ($this->suppliers as $supplier) {
            Supplier::firstOrCreate(
                ['name' => $supplier['name']],
                $supplier
            );
        }
    }
}
