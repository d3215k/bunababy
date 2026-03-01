<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('treatments')->insert([
            [
                'category_id' => 1,
                'name' => 'Persiapan Persalinan',
                'desc' => 'Private pembahasan materi persiapan persalinan, teknik pernafasan, dan tips persalinan nyaman, mendapatkan modul',
                // 'price' => 200000,
                'duration' => 120,
                'sort' => 1,
            ],
            [
                'category_id' => 1,
                'name' => 'Kelas Laktasi',
                'desc' => 'Private pembahasan materi laktasi dan manajemen ASI, mendapatkan modul',
                // 'price' => 200000,
                'duration' => 120,
                'sort' => 2,
            ],
            [
                'category_id' => 1,
                'name' => 'New Born Care',
                // 'price' => 200000,
                'duration' => 120,
                'desc' => 'Private pembahasan materi perawatan bayi baru lahir, simulasi peawatan bayi sehari-hari, dan teknik menggendong bayi',
                'sort' => 3,
            ],
            [
                'category_id' => 1,
                'name' => 'Tumbuh Kembang Anak',
                // 'price' => 150000,
                'duration' => 120,
                'desc' => 'Private pembahasa materi tumbuh kembang anak sesuai dengan usia anak, cara stimulasi di rumah, dan simulasi praktek',
                'sort' => 4,
            ],

            [
                'category_id' => 2,
                'name' => 'Baby SPA',
                // 'price' => 160000,
                'duration' => 90,
                'desc' => 'Pijat bayi, baby gym (senam bayi) dan baby swim (berenang)',
                'sort' => 1,
            ],
            [
                'category_id' => 2,
                'name' => 'Cukur Bayi',
                // 'price' => 60000,
                'duration' => 30,
                'desc' => 'Cukur bayi (usia 0-12 bulan)',
                'sort' => 2,
            ],
            [
                'category_id' => 2,
                'name' => 'Tindik',
                // 'price' => 60000,
                'duration' => 15,
                'desc' => 'Tindik dengan teknik steril manual langsung dengan anting plenis (tidak termasuk antingnya)',
                'sort' => 3,
            ],
            [
                'category_id' => 2,
                'name' => 'Tumbuh Kembang Anak',
                // 'price' => 35000,
                'duration' => 30,
                'desc' => 'Pemeriksaan tumbuh kembang anak sesuai usianya dengan pedoman khusus',
                'sort' => 4,
            ],
            [
                'category_id' => 2,
                'name' => 'Mandi Ceria',
                // 'price' => 30000,
                'duration' => 30,
                'desc' => 'Memandikanbayi dan edukasi cara memandikan bayi untu orangtua',
                'sort' => 5,
            ],
            [
                'category_id' => 2,
                'name' => 'Pijat Bayi Sehat',
                // 'price' => 90000,
                'duration' => 60,
                'desc' => 'Pijat bayi (usia 0-12 bulan) seluruh tubuh dengan aromaterapi',
                'sort' => 6,
            ],
            [
                'category_id' => 2,
                'name' => 'Pijat Balita',
                // 'price' => 90000,
                'duration' => 60,
                'desc' => 'Pijat bayi (usia 13-60 bulan) seluruh tubuh dengan aromaterapi',
                'sort' => 7,
            ],
            [
                'category_id' => 2,
                'name' => 'Baby Package',
                // 'price' => 105000,
                'duration' => 60,
                'desc' => 'Pijat bayi seluruh tubuh, brain gym untuk kecerasan otak dan baby gym untuk melatih motorik bayi',
                'sort' => 8,
            ],
            [
                'category_id' => 2,
                'name' => 'Pijat Pediatrik',
                // 'price' => 100000,
                'duration' => 60,
                'desc' => 'Pijat seluruh tubuh serta penekanan di titik pediatrik sesuai kondisi bayi. Pilihan : Pediatrk nafsu makan, kembung, batuk, pilek, diare, konstipasi, demam, imun booster',
                'sort' => 9,
            ],
            [
                'category_id' => 2,
                'name' => 'Pijat Stimulasi Anak',
                // 'price' => 110000,
                'duration' => 60,
                'desc' => 'Pijat seluruh tubuh, cek tumbuh kembang anak, stimulasi motorik, Pilihan : stimulasi tengkurap, duduk, merangkak, jalan (usia 0-24 bulan)',
                'sort' => 10,
            ],

            [
                'category_id' => 3,
                'name' => 'Prenatal Gentle Yoga',
                // 'price' => 120000,
                'duration' => 60,
                'desc' => 'Private class yoga khusus ibu hamil dan periksa hamil',
                'sort' => 1,
            ],
            [
                'category_id' => 3,
                'name' => 'Paket Laktasi',
                // 'price' => 85000,
                'duration' => 60,
                'desc' => 'Breastcare, pijat punggung titik oksitosin, dan konsultasi ASI',
                'sort' => 2,
            ],
            [
                'category_id' => 3,
                'name' => 'Pijat Gelombang Cinta',
                // 'price' => 85000,
                'duration' => 60,
                'desc' => 'Pijat untuk menstimulasi kontraksi alami bumil (min 37 minggu), periksa hamil, afirmasi untuk persalinan lancar',
                'sort' => 3,
            ],
            [
                'category_id' => 3,
                'name' => 'Totok Bersinar',
                // 'price' => 60000,
                'duration' => 30,
                'desc' => 'Cleansing, pijat wajah, totok wajah, masker coklat',
                'sort' => 4,
            ],
            [
                'category_id' => 3,
                'name' => 'Happy Pregnancy',
                // 'price' => 150000,
                'duration' => 90,
                'desc' => 'Pijat ibu hamil (mulai usia 20 minggu) seluruh tubuh, kecuali beberapa titik kontraindikasi bumil, periksa ibu hamil, dan totok wajah',
                'sort' => 5,
            ],
            [
                'category_id' => 3,
                'name' => 'Happy Mommy',
                // 'price' => 150000,
                'duration' => 90,
                'desc' => 'Pijat ibu nifas (0-42 hari nifas) seluruh tubuh, periksa nifas sesuai keadaan pasien, dan totok wajah',
                'sort' => 6,
            ],
            [
                'category_id' => 3,
                'name' => 'Pijat Rintik Hujan',
                // 'price' => 450000,
                'duration' => 120,
                'desc' => 'Pijat premium dengan teknik khusus dan menggunakan full paket oil young living dengan sensasi hangat di tubuh. Untuk semua usia dewasa khusus perempuan, berfungsi untuk detoksifikasi tubuh, meningkatkan daya tahan tubuh, mengurangi ketegangan badan dan rileksasi pikiran. Free totok wajah',
                'sort' => 7,
            ],
        ]);
    }
}
