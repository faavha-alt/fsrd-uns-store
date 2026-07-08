<?php

namespace Database\Seeders;

use App\Models\TrainingClass;
use App\Models\ClassSchedule;
use App\Models\Category;
use App\Models\Creator;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TrainingClassSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : null;

        $classes = [
            [
                'name'        => 'Pelatihan Membatik — Teknik Tulis untuk Pemula',
                'category'    => 'Pelatihan Batik',
                'instructor'  => 'Sari Indah Pertiwi',
                'description' => 'Kelas membatik teknik tulis untuk pemula. Peserta akan belajar membuat pola, mencanting, dan proses pewarnaan dasar. Semua bahan disediakan oleh panitia.',
                'syllabus'    => "1. Pengenalan alat dan bahan batik tulis\n2. Membuat pola motif sederhana\n3. Teknik mencanting dasar\n4. Proses pewarnaan dengan napthol\n5. Pelorodan dan finishing",
                'price'       => 350000,
                'schedules'   => [
                    ['date' => now()->addDays(14)->format('Y-m-d'), 'start' => '09:00', 'end' => '15:00', 'location' => 'Studio Batik FSRD UNS, Gedung B Lt. 2', 'quota' => 15],
                    ['date' => now()->addDays(28)->format('Y-m-d'), 'start' => '09:00', 'end' => '15:00', 'location' => 'Studio Batik FSRD UNS, Gedung B Lt. 2', 'quota' => 15],
                ],
            ],
            [
                'name'        => 'Workshop Keramik — Teknik Putar & Glasir',
                'category'    => 'Pelatihan Keramik',
                'instructor'  => 'Prof. Hadi Santoso',
                'description' => 'Workshop intensif keramik menggunakan meja putar (wheel throwing) dan teknik glasir. Peserta akan membawa pulang karya hasil sendiri setelah pembakaran.',
                'syllabus'    => "1. Pengenalan tanah liat dan karakteristiknya\n2. Teknik wedging (menguleni tanah liat)\n3. Pembentukan dengan meja putar\n4. Teknik trimming dan finishing\n5. Aplikasi glasir dan pembakaran",
                'price'       => 500000,
                'schedules'   => [
                    ['date' => now()->addDays(10)->format('Y-m-d'), 'start' => '08:00', 'end' => '14:00', 'location' => 'Lab Keramik FSRD UNS, Gedung C', 'quota' => 10],
                    ['date' => now()->addDays(24)->format('Y-m-d'), 'start' => '08:00', 'end' => '14:00', 'location' => 'Lab Keramik FSRD UNS, Gedung C', 'quota' => 10],
                ],
            ],
            [
                'name'        => 'Kelas Melukis Cat Minyak — Teknik Landscape',
                'category'    => 'Pelatihan Melukis',
                'instructor'  => 'Budi Wicaksono, M.Sn.',
                'description' => 'Kelas melukis landscape menggunakan cat minyak di atas kanvas. Cocok untuk pemula hingga menengah. Peserta membawa kanvas 30x40cm, cat dan kuas disediakan panitia.',
                'syllabus'    => "1. Pengenalan cat minyak dan medium\n2. Komposisi dan perspektif dasar\n3. Teknik underpainting\n4. Layering warna dan tekstur\n5. Detail dan finishing karya",
                'price'       => 450000,
                'schedules'   => [
                    ['date' => now()->addDays(7)->format('Y-m-d'), 'start' => '09:00', 'end' => '16:00', 'location' => 'Studio Seni Murni FSRD UNS', 'quota' => 12],
                    ['date' => now()->addDays(21)->format('Y-m-d'), 'start' => '09:00', 'end' => '16:00', 'location' => 'Studio Seni Murni FSRD UNS', 'quota' => 12],
                ],
            ],
            [
                'name'        => 'Komputer Grafis — Adobe Illustrator untuk Pemula',
                'category'    => 'Komputer Grafis',
                'instructor'  => 'Dr. Rizky Dwi Anggara',
                'description' => 'Kelas desain grafis menggunakan Adobe Illustrator. Peserta akan belajar membuat logo, ilustrasi vektor, dan layout sederhana. Laptop diperlukan (spesifikasi minimum diberitahukan saat konfirmasi).',
                'syllabus'    => "1. Antarmuka dan tools dasar Illustrator\n2. Membuat dan memanipulasi shape\n3. Penggunaan warna dan gradient\n4. Teknik tracing dan ilustrasi\n5. Typography dan layout\n6. Export dan persiapan file untuk cetak",
                'price'       => 650000,
                'schedules'   => [
                    ['date' => now()->addDays(5)->format('Y-m-d'), 'start' => '08:00', 'end' => '15:00', 'location' => 'Lab Komputer DKV FSRD UNS, Gedung A Lt. 3', 'quota' => 20],
                    ['date' => now()->addDays(12)->format('Y-m-d'), 'start' => '08:00', 'end' => '15:00', 'location' => 'Lab Komputer DKV FSRD UNS, Gedung A Lt. 3', 'quota' => 20],
                ],
            ],
            [
                'name'        => 'Fotografi Produk — Teknik Lighting & Komposisi',
                'category'    => 'Fotografi',
                'instructor'  => 'Dr. Rizky Dwi Anggara',
                'description' => 'Workshop fotografi produk untuk keperluan e-commerce dan media sosial. Peserta belajar teknik pencahayaan, komposisi, dan editing dasar. Kamera DSLR/mirrorless diperlukan.',
                'syllabus'    => "1. Dasar-dasar fotografi produk\n2. Teknik pencahayaan natural dan artificial\n3. Komposisi dan styling produk\n4. Setting kamera untuk produk\n5. Editing dasar dengan Lightroom",
                'price'       => 400000,
                'schedules'   => [
                    ['date' => now()->addDays(18)->format('Y-m-d'), 'start' => '09:00', 'end' => '14:00', 'location' => 'Studio Foto FSRD UNS', 'quota' => 12],
                ],
            ],
            [
                'name'        => 'Desain Furniture — Dari Konsep ke Prototype',
                'category'    => 'Desain Furniture',
                'instructor'  => 'Dian Kusuma Wardani, M.Ds.',
                'description' => 'Kelas desain furniture dari proses konseptualisasi hingga pembuatan prototype skala kecil. Peserta akan belajar membuat gambar teknik dan model 3D sederhana.',
                'syllabus'    => "1. Prinsip desain furniture ergonomis\n2. Gambar teknik dan detail konstruksi\n3. Pemilihan material dan finishing\n4. Pemodelan 3D sederhana dengan SketchUp\n5. Pembuatan prototype skala 1:10",
                'price'       => 750000,
                'schedules'   => [
                    ['date' => now()->addDays(20)->format('Y-m-d'), 'start' => '08:00', 'end' => '16:00', 'location' => 'Workshop Desain Interior FSRD UNS', 'quota' => 10],
                ],
            ],
        ];

        foreach ($classes as $data) {
            $category = Category::where('name', $data['category'])
                ->where('type', 'pelatihan')->first();
            $instructor = Creator::where('name', 'like', '%'.$data['instructor'].'%')->first();

            if (!$category || !$instructor) continue;

            $class = TrainingClass::firstOrCreate(
                ['slug' => Str::slug($data['name']) . '-' . Str::random(5)],
                [
                    'name'        => $data['name'],
                    'category_id' => $category->id,
                    'creator_id'  => $instructor->id,
                    'curator_id'  => $adminId,
                    'description' => $data['description'],
                    'syllabus'    => $data['syllabus'],
                    'price'       => $data['price'],
                    'image'       => null,
                    'status'      => 'approved',
                ]
            );

            foreach ($data['schedules'] as $schedule) {
                ClassSchedule::firstOrCreate(
                    [
                        'training_class_id' => $class->id,
                        'date'              => $schedule['date'],
                    ],
                    [
                        'start_time'   => $schedule['start'],
                        'end_time'     => $schedule['end'],
                        'location'     => $schedule['location'],
                        'quota'        => $schedule['quota'],
                        'booked_count' => 0,
                    ]
                );
            }
        }

        $this->command->info('✅ Kelas pelatihan & jadwal berhasil dibuat!');
    }
}