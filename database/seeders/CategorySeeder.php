<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $produk = [
            'Lukisan', 'Patung', 'Keramik', 'Furniture', 'Batik',
            'Fotografi', 'Desain Grafis',
        ];

        $pelatihan = [
    'Pelatihan Batik',
    'Pelatihan Keramik',
    'Pelatihan Melukis',
    'Komputer Grafis',
    'Pelatihan Fotografi',
    'Pelatihan Desain Furniture',
];

        foreach ($produk as $name) {
    Category::firstOrCreate(
        ['slug' => Str::slug($name) . '-produk', 'type' => 'produk'],
        ['name' => $name]
    );
}

foreach ($pelatihan as $name) {
    Category::firstOrCreate(
        ['slug' => Str::slug($name) . '-pelatihan', 'type' => 'pelatihan'],
        ['name' => $name]
    );
}

        $this->command->info('✅ Kategori berhasil dibuat!');
    }
}
