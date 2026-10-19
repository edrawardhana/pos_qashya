<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['GMS-001', "Gamis Syar'i Aisyah", 'Gamis', 285000, 14, 5, 'Gamis syar\'i bahan wolfis premium, busui friendly.'],
            ['GMS-002', 'Gamis Linen Humaira', 'Gamis', 325000, 4, 5, 'Gamis linen adem dengan detail kancing depan.'],
            ['HJB-001', 'Pashmina Ceruty Babydoll', 'Hijab', 45000, 62, 15, 'Pashmina ceruty 175x75 cm, mudah dibentuk.'],
            ['HJB-002', 'Segiempat Voal Motif', 'Hijab', 65000, 38, 15, 'Hijab segiempat voal lasercut motif floral.'],
            ['HJB-003', 'Bergo Instan Jersey', 'Hijab', 35000, 9, 10, 'Bergo instan jersey dengan pet, tanpa peniti.'],
            ['TNK-001', 'Tunik Rayon Nadia', 'Tunik', 149000, 21, 6, 'Tunik katun rayon motif, panjang selutut.'],
            ['TNK-002', 'Tunik Crinkle Salsa', 'Tunik', 165000, 0, 5, 'Tunik crinkle airflow, tidak perlu disetrika.'],
            ['OTR-001', 'Outer Kimono Basic', 'Outer', 175000, 12, 4, 'Outer kimono polos bahan moscrepe.'],
            ['OTR-002', 'Cardigan Rajut Zahra', 'Outer', 189000, 7, 4, 'Cardigan rajut halus, cocok untuk harian.'],
            ['MKN-001', 'Mukena Katun Jepang', 'Mukena', 245000, 10, 3, 'Mukena katun jepang lengkap dengan tas.'],
            ['AKS-001', 'Inner Ninja Rajut', 'Aksesoris', 15000, 85, 20, 'Ciput ninja rajut, elastis dan nyaman.'],
            ['AKS-002', 'Bros Mutiara Set', 'Aksesoris', 25000, 3, 10, 'Set 3 bros mutiara untuk hijab.'],
        ];

        foreach ($products as [$sku, $name, $category, $price, $stock, $minStock, $description]) {
            Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name' => $name,
                    'category' => $category,
                    'price' => $price,
                    'stock' => $stock,
                    'min_stock' => $minStock,
                    'description' => $description,
                ]
            );
        }
    }
}