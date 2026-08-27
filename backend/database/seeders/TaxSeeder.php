<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $taxes = [
            ['name' => 'PDV 20%', 'rate' => 20.00, 'is_default' => true, 'is_active' => true],
            ['name' => 'PDV 10%', 'rate' => 10.00, 'is_default' => false, 'is_active' => true],
            ['name' => 'PDV 0%', 'rate' => 0.00, 'is_default' => false, 'is_active' => true],
        ];

        foreach ($taxes as $tax) {
            Tax::query()->updateOrCreate(
                ['name' => $tax['name']],
                $tax
            );
        }
    }
}
