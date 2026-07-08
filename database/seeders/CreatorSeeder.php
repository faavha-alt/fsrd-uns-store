<?php

namespace Database\Seeders;

use App\Models\Creator;
use Illuminate\Database\Seeder;

class CreatorSeeder extends Seeder
{
    public function run(): void
    {
        $creators = [
            [
                'name'       => 'Dr. Rizky Dwi Anggara, M.Sn.',
                'type'       => 'dosen',
                'department' => 'Desain Komunikasi Visual',
                'bio'        => 'Dosen senior DKV FSRD UNS dengan spesialisasi desain grafis kontemporer dan tipografi. Telah menghasilkan lebih dari 50 karya yang dipamerkan di tingkat nasional dan internasional.',
                'photo'      => null,
            ],
            [
                'name'       => 'Prof. Hadi Santoso, M.Si.',
                'type'       => 'dosen',
                'department' => 'Kriya Seni',
                'bio'        => 'Guru Besar bidang Kriya Seni dengan keahlian keramik dan seni terapan. Aktif dalam berbagai pameran seni internasional dan menjadi instruktur workshop keramik.',
                'photo'      => null,
            ],
            [
                'name'       => 'Dian Kusuma Wardani, M.Ds.',
                'type'       => 'dosen',
                'department' => 'Desain Interior',
                'bio'        => 'Desainer interior dan furniture dengan pendekatan ergonomis modern. Karyanya menggabungkan estetika kontemporer dengan kearifan lokal budaya Jawa.',
                'photo'      => null,
            ],
            [
                'name'       => 'Budi Wicaksono, M.Sn.',
                'type'       => 'dosen',
                'department' => 'Seni Murni',
                'bio'        => 'Pelukis dengan aliran realisme kontemporer. Spesialisasi dalam lukisan cat minyak bertema budaya dan kehidupan masyarakat Indonesia.',
                'photo'      => null,
            ],
            [
                'name'       => 'Sari Indah Pertiwi, M.Sn.',
                'type'       => 'dosen',
                'department' => 'Kriya Tekstil',
                'bio'        => 'Peneliti dan praktisi batik dengan fokus pada inovasi motif batik kontemporer berbasis kecerdasan buatan dan teknologi digital.',
                'photo'      => null,
            ],
            [
                'name'       => 'Anisa Latifah',
                'type'       => 'mahasiswa',
                'department' => 'Kriya Seni',
                'bio'        => 'Mahasiswa semester 7 Kriya Seni FSRD UNS. Fokus pada karya keramik dengan motif batik tradisional yang dipadukan dengan teknik glasir modern.',
                'photo'      => null,
            ],
            [
                'name'       => 'Fajar Nugroho',
                'type'       => 'mahasiswa',
                'department' => 'Desain Komunikasi Visual',
                'bio'        => 'Mahasiswa DKV yang aktif di bidang ilustrasi digital dan desain identitas visual. Karya-karyanya telah memenangkan beberapa kompetisi desain nasional.',
                'photo'      => null,
            ],
            [
                'name'       => 'Maya Puspita Sari',
                'type'       => 'mahasiswa',
                'department' => 'Seni Murni',
                'bio'        => 'Mahasiswa Seni Murni dengan ketertarikan pada seni lukis ekspresionisme. Aktif berpameran di galeri-galeri di Surakarta dan Yogyakarta.',
                'photo'      => null,
            ],
            [
                'name'       => 'Rendra Pratama',
                'type'       => 'mahasiswa',
                'department' => 'Desain Interior',
                'bio'        => 'Mahasiswa Desain Interior dengan passion di furnitur berbahan kayu lokal. Menggabungkan teknik tradisional dengan desain minimalis modern.',
                'photo'      => null,
            ],
        ];

        foreach ($creators as $data) {
            Creator::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        $this->command->info('✅ Kreator berhasil dibuat!');
    }
}
