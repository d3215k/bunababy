<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kecamatans')->insert([
            ['kabupaten_id' => 1, 'name' => 'Cimahi Selatan', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 1, 'name' => 'Cimahi Tengah', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 1, 'name' => 'Cimahi Utara', 'distance' => rand(1, 20)],

            ['kabupaten_id' => 2, 'name' => 'Andir', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Astana Anyar', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Antapani', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Arcamanik', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Babakan Ciparay', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Bandung Kidul', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Bandung Kulon', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Batununggal', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Bojongloa Kaler', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Bojongloa Kidul', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Buahbatu', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Cibeunying Kaler', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Cibeunying Kidul', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Cibiru', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Cicendo', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Cidadap', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Cinambo', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Coblong', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Gedebage', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Kiaracondong', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Lengkong', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Mandalajati', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Panyileukan', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Rancasari', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Regol', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Sukajadi', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Sukasari', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Sumur Bandung', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 2, 'name' => 'Ujungberung', 'distance' => rand(1, 20)],

            ['kabupaten_id' => 3, 'name' => 'Arjasari', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Baleendah', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Banjaran', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Bojongsoang', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Cangkuang', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Cicalengka', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Cikancung', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Cilengkrang', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Cileunyi', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Cimaung', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Cimenyan', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Ciparay', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Ciwidey', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Dayeuhkolot', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Ibun', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Katapang', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Kertasari', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Kutawaringin', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Majalaya', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Margaasih', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Margahayu', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Nagreg', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Pacet', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Pameungpeuk', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Pangalengan', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Paseh', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Pasirjambu', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Rancabali', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Rancaekek', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Solokanjeruk', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 3, 'name' => 'Soreang', 'distance' => rand(1, 20)],

            ['kabupaten_id' => 4, 'name' => 'Batujajar', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Cihampelar', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Cikalong Wetan', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Cililin', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Cipatat', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Cipeundeuy', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Cipongkor', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Cisarua', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Gununghalu', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Lembang', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Ngamprah', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Padalarang', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Parongpong', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Rongga', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Saguling', 'distance' => rand(1, 20)],
            ['kabupaten_id' => 4, 'name' => 'Sindangkerta', 'distance' => rand(1, 20)],

            ['kabupaten_id' => 2, 'name' => 'Bandung Wetan', 'distance' => rand(1, 20)],

        ]);
    }
}
