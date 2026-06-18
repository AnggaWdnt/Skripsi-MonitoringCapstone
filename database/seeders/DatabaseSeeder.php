<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Initial Prodis
        $initialProdis = [
            'Teknologi Informasi',
            'Informatika',
            'Sistem Informasi',
            'Teknik Komputer',
        ];

        foreach ($initialProdis as $name) {
            \App\Models\Prodi::create(['name' => $name]);
        }

        // Create Admin
        User::create([
            'name' => 'Admin Program Studi',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create Dosen
        $dosen = User::create([
            'name' => 'Drs. H. Bambang, M.T.',
            'email' => 'dosen@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'dosen',
            'nidn' => '11223344',
            'prodi' => 'Teknologi Informasi',
        ]);

        // Create another Dosen for testing selection
        User::create([
            'name' => 'Dr. Siti Aminah, M.Kom.',
            'email' => 'siti@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'dosen',
            'nidn' => '55667788',
            'prodi' => 'Teknologi Informasi',
        ]);

        // Create Capstone Group (assigned by Prodi)
        $group = \App\Models\Group::create([
            'group_name' => 'Kelompok Capstone TI-01',
            'dosen_id' => $dosen->id,
            'status' => 'belum_mengajukan',
        ]);

        // Create Mahasiswa 1 (Angga)
        User::create([
            'name' => 'Angga Pratama',
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'nim' => '20220140',
            'prodi' => 'Teknologi Informasi',
            'angkatan' => '2022',
            'status_pkl' => 'belum',
            'group_id' => $group->id,
        ]);

        // Create Mahasiswa 2 (Budi - Group Member)
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'nim' => '20220141',
            'prodi' => 'Teknologi Informasi',
            'angkatan' => '2022',
            'status_pkl' => 'belum',
            'group_id' => $group->id,
        ]);

        // Dosen 2 Siti Aminah
        $dosen2 = User::where('email', 'siti@gmail.com')->first();

        // Create Group 2 (TI-02, pending title approval)
        $group2 = \App\Models\Group::create([
            'group_name' => 'Kelompok Capstone TI-02',
            'dosen_id' => $dosen2->id,
            'title' => 'Sistem Rekomendasi Destinasi Wisata Menggunakan Collaborative Filtering',
            'description' => 'Aplikasi web untuk memberikan rekomendasi destinasi wisata di Yogyakarta berdasarkan rating pengguna lain.',
            'survey_file' => 'uploads/survey/dummy_survey.pdf',
            'status' => 'pending',
        ]);

        // Mahasiswa 3 (Dewi - Member of Group 2)
        User::create([
            'name' => 'Dewi Sartika',
            'email' => 'dewi@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'nim' => '20220142',
            'prodi' => 'Teknologi Informasi',
            'angkatan' => '2022',
            'status_pkl' => 'belum',
            'group_id' => $group2->id,
        ]);

        // Mahasiswa 4 (Eko - Member of Group 2)
        User::create([
            'name' => 'Eko Prasetyo',
            'email' => 'eko@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'nim' => '20220143',
            'prodi' => 'Teknologi Informasi',
            'angkatan' => '2022',
            'status_pkl' => 'belum',
            'group_id' => $group2->id,
        ]);

        // Mahasiswa 5 (Cindy - Unassigned Student, has no group yet)
        User::create([
            'name' => 'Cindy Lestari',
            'email' => 'cindy@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'nim' => '20220144',
            'prodi' => 'Teknologi Informasi',
            'angkatan' => '2022',
            'status_pkl' => 'belum',
            'group_id' => null,
        ]);

        // Create logbooks for Dewi
        $dewi = User::where('email', 'dewi@gmail.com')->first();
        \App\Models\Logbook::create([
            'mahasiswa_id' => $dewi->id,
            'date' => now()->subDays(2),
            'activity' => 'Melakukan survei lapangan ke beberapa destinasi wisata di Yogyakarta untuk mengumpulkan data rating awal.',
            'status' => 'approved',
        ]);
        \App\Models\Logbook::create([
            'mahasiswa_id' => $dewi->id,
            'date' => now()->subDay(),
            'activity' => 'Mulai menyusun bab 1 pendahuluan dan mendefinisikan rumusan masalah proyek Capstone.',
            'status' => 'pending',
        ]);

        // Create logbooks for Eko
        $eko = User::where('email', 'eko@gmail.com')->first();
        \App\Models\Logbook::create([
            'mahasiswa_id' => $eko->id,
            'date' => now()->subDays(2),
            'activity' => 'Mempelajari algoritma Collaborative Filtering dan membuat prototipe sederhana sistem rekomendasi di Python.',
            'status' => 'approved',
        ]);
        \App\Models\Logbook::create([
            'mahasiswa_id' => $eko->id,
            'date' => now(),
            'activity' => 'Mengintegrasikan database SQLite awal untuk menyimpan preferensi rating destinasi wisata.',
            'status' => 'pending',
        ]);
    }
}
