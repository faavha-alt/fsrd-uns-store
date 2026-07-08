<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Creator;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : null;

        $products = [
            // Lukisan
            [
                'name'        => 'Lukisan Batik Kontemporer — Seri Parang',
                'category'    => 'Lukisan',
                'creator'     => 'Budi Wicaksono, M.Sn.',
                'description' => 'Lukisan cat minyak di atas kanvas berukuran 60x80cm yang menampilkan motif batik parang dalam interpretasi kontemporer. Menggunakan teknik impasto untuk menciptakan tekstur yang kaya dan dimensi visual yang mendalam.',
                'price'       => 1500000,
                'stock'       => 1,
            ],
            [
                'name'        => 'Potret Ekspresionis — Identitas Budaya Jawa',
                'category'    => 'Lukisan',
                'creator'     => 'Maya Puspita Sari',
                'description' => 'Lukisan ekspresionisme yang mengeksplorasi identitas budaya Jawa melalui figur manusia dengan latar motif tradisional. Media cat akrilik di atas kanvas 50x70cm.',
                'price'       => 850000,
                'stock'       => 1,
            ],
            // Keramik
            [
                'name'        => 'Keramik Vas Batik Tulis Motif Kawung',
                'category'    => 'Keramik',
                'creator'     => 'Anisa Latifah',
                'description' => 'Vas keramik dengan teknik putar yang dihiasi motif batik kawung melalui proses ukir dan glasir. Tinggi 35cm dengan diameter 20cm. Setiap karya unik dan berbeda.',
                'price'       => 450000,
                'stock'       => 3,
            ],
            [
                'name'        => 'Set Piring Keramik Motif Mega Mendung',
                'category'    => 'Keramik',
                'creator'     => 'Prof. Hadi Santoso, M.Si.',
                'description' => 'Set 4 piring makan keramik dengan motif mega mendung khas Cirebon yang diinterpretasikan ulang dalam nuansa warna biru-hijau kontemporer. Aman untuk makanan dan microwave.',
                'price'       => 680000,
                'stock'       => 5,
            ],
            // Batik
            [
                'name'        => 'Kain Batik Tulis Premium — Motif Sido Mukti',
                'category'    => 'Batik',
                'creator'     => 'Sari Indah Pertiwi, M.Sn.',
                'description' => 'Kain batik tulis tangan dengan motif sido mukti menggunakan malam berkualitas tinggi. Panjang 2,5 meter x 1,1 meter. Proses pembuatan 2-3 minggu dengan pewarna alam.',
                'price'       => 2500000,
                'stock'       => 2,
            ],
            [
                'name'        => 'Batik Cap Kontemporer — Seri Geometri',
                'category'    => 'Batik',
                'creator'     => 'Sari Indah Pertiwi, M.Sn.',
                'description' => 'Kain batik cap dengan motif geometris modern yang terinspirasi dari pola-pola tradisional Jawa. Panjang 2 meter x 1,1 meter. Cocok untuk busana formal maupun kasual.',
                'price'       => 450000,
                'stock'       => 8,
            ],
            // Furniture
            [
                'name'        => 'Kursi Kayu Jati Desain Ergonomis Modern',
                'category'    => 'Furniture',
                'creator'     => 'Dian Kusuma Wardani, M.Ds.',
                'description' => 'Kursi makan dari kayu jati pilihan dengan desain ergonomis yang menggabungkan estetika Jawa dan kenyamanan modern. Finishing natural oil. Ukuran: 45x45x85cm.',
                'price'       => 3200000,
                'stock'       => 2,
            ],
            [
                'name'        => 'Meja Kopi Kayu Sengon — Minimalis Natural',
                'category'    => 'Furniture',
                'creator'     => 'Rendra Pratama',
                'description' => 'Meja kopi minimalis dari kayu sengon lokal dengan kaki besi hitam. Desain simpel namun elegan, cocok untuk ruang tamu modern. Ukuran: 90x50x40cm.',
                'price'       => 1800000,
                'stock'       => 3,
            ],
            // Desain Grafis
            [
                'name'        => 'Template Identitas Visual — Paket Lengkap',
                'category'    => 'Desain Grafis',
                'creator'     => 'Fajar Nugroho',
                'description' => 'Paket template identitas visual lengkap termasuk logo, kartu nama, kop surat, dan panduan brand. Format file AI, EPS, PDF. Cocok untuk UMKM dan startup.',
                'price'       => 350000,
                'stock'       => 999,
            ],
            [
                'name'        => 'Ilustrasi Digital — Seri Wayang Kontemporer',
                'category'    => 'Desain Grafis',
                'creator'     => 'Fajar Nugroho',
                'description' => 'Set 5 ilustrasi digital tokoh wayang dalam gaya kontemporer. File digital beresolusi tinggi (300dpi) format PNG dengan background transparan. Lisensi personal use.',
                'price'       => 200000,
                'stock'       => 999,
            ],
            // Fotografi
            [
                'name'        => 'Fine Art Photography — Seri Batik & Tenun',
                'category'    => 'Fotografi',
                'creator'     => 'Dr. Rizky Dwi Anggara, M.Sn.',
                'description' => 'Set 10 foto fine art bertema kain tradisional Indonesia (batik dan tenun) dalam format digital beresolusi tinggi. Cocok untuk dekorasi, editorial, atau referensi desain.',
                'price'       => 500000,
                'stock'       => 999,
            ],
            [
                'name'        => 'Cetak Foto Fine Art — Pemandangan Surakarta',
                'category'    => 'Fotografi',
                'creator'     => 'Maya Puspita Sari',
                'description' => 'Cetak foto fine art pemandangan ikonik Kota Surakarta di atas kertas foto premium. Ukuran A2 (42x59cm) dengan frame kayu natural. Edisi terbatas 10 eksemplar.',
                'price'       => 750000,
                'stock'       => 4,
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('name', $data['category'])
                ->where('type', 'produk')->first();
            $creator = Creator::where('name', 'like', '%'.$data['creator'].'%')->first();

            if (!$category || !$creator) continue;

            Product::firstOrCreate(
                ['slug' => Str::slug($data['name']) . '-' . Str::random(5)],
                [
                    'name'        => $data['name'],
                    'category_id' => $category->id,
                    'creator_id'  => $creator->id,
                    'curator_id'  => $adminId,
                    'description' => $data['description'],
                    'price'       => $data['price'],
                    'stock'       => $data['stock'],
                    'images'      => null,
                    'status'      => 'approved',
                ]
            );
        }

        $this->command->info('✅ Produk berhasil dibuat!');
    }
}
