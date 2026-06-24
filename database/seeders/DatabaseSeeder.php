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
        // Create Initial Themes
        $initialThemes = [
            'peningkatan layanan pendidikan formal informal',
            'perbaikan atau peningkatan proses monitoring dan layanan publik',
            'peningkatan produktivitas umkm',
            'peningkatan produktivitas pertanian perikanan perkebunan',
        ];

        foreach ($initialThemes as $name) {
            \App\Models\Theme::create(['name' => $name]);
        }

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

        // Create Capstone Group 1 (assigned by Prodi) - Approved
        $group = \App\Models\Group::create([
            'group_name' => 'Kelompok Capstone TI-01',
            'theme' => 'perbaikan atau peningkatan proses monitoring dan layanan publik',
            'dosen_id' => $dosen->id,
            'title' => 'Sistem Monitoring Kinerja Panel Surya Berbasis Internet of Things (IoT)',
            'description' => 'Sistem pemantauan parameter kelistrikan panel surya secara real-time menggunakan protokol MQTT dan divisualisasikan dalam bentuk web dashboard.',
            'status' => 'approved',
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

        // Seed Logbooks for Group 1 (Angga and Budi)
        $angga = User::where('email', 'mahasiswa@gmail.com')->first();
        $budi = User::where('email', 'budi@gmail.com')->first();

        // 1. Pendahuluan for Group 1 -> Selesai
        \App\Models\Logbook::create([
            'mahasiswa_id' => $angga->id,
            'date' => now()->subDays(5),
            'activity' => 'Melakukan studi literatur mengenai arsitektur sistem monitoring IoT panel surya.',
            'section' => 'Pendahuluan / Latar Belakang',
            'documentation' => 'uploads/laporan/bab1_pendahuluan_iot.pdf',
            'status' => 'approved',
            'dosen_note' => 'Pendahuluan sudah terstruktur dengan baik.',
        ]);

        // 2. Tinjauan Pustaka for Group 1 -> Selesai
        \App\Models\Logbook::create([
            'mahasiswa_id' => $budi->id,
            'date' => now()->subDays(3),
            'activity' => 'Mengkaji teori sensor arus ACS712, sensor tegangan, dan mikrokontroler ESP32.',
            'section' => 'Tinjauan Pustaka / Landasan Teori',
            'documentation' => 'uploads/laporan/bab2_tinjauan_pustaka_iot.pdf',
            'status' => 'approved',
        ]);

        // 3. Metodologi for Group 1 -> Review
        \App\Models\Logbook::create([
            'mahasiswa_id' => $angga->id,
            'date' => now()->subDay(),
            'activity' => 'Merancang skema perkabelan hardware IoT dan topologi jaringan komunikasi MQTT.',
            'section' => 'Metodologi / Perancangan Sistem',
            'documentation' => 'uploads/laporan/bab3_metodologi_iot.docx',
            'status' => 'pending',
        ]);

        // Dosen 2 Siti Aminah
        $dosen2 = User::where('email', 'siti@gmail.com')->first();

        // Create Group 2 (TI-02, approved title)
        $group2 = \App\Models\Group::create([
            'group_name' => 'Kelompok Capstone TI-02',
            'theme' => 'peningkatan produktivitas umkm',
            'dosen_id' => $dosen2->id,
            'title' => 'Sistem Rekomendasi Destinasi Wisata Menggunakan Collaborative Filtering',
            'description' => 'Aplikasi web untuk memberikan rekomendasi destinasi wisata di Yogyakarta berdasarkan rating pengguna lain.',
            'status' => 'approved',
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

        // Create logbooks for Dewi & Eko (Group 2) covering ALL 6 SECTIONS
        $dewi = User::where('email', 'dewi@gmail.com')->first();
        $eko = User::where('email', 'eko@gmail.com')->first();
        
        // 1. Pendahuluan / Latar Belakang -> Selesai (Approved)
        \App\Models\Logbook::create([
            'mahasiswa_id' => $dewi->id,
            'date' => now()->subDays(10),
            'activity' => 'Melakukan analisis latar belakang masalah perlunya sistem rekomendasi pariwisata di Yogyakarta.',
            'section' => 'Pendahuluan / Latar Belakang',
            'documentation' => 'uploads/laporan/draft_bab1_pendahuluan.pdf',
            'status' => 'approved',
            'dosen_note' => 'Latar belakang masalah sudah sangat jelas dan relevan.',
        ]);

        // 2. Tinjauan Pustaka / Landasan Teori -> Selesai (Approved)
        \App\Models\Logbook::create([
            'mahasiswa_id' => $dewi->id,
            'date' => now()->subDays(8),
            'activity' => 'Mengkaji referensi jurnal terkait metode Collaborative Filtering dan algoritma rekomendasi pariwisata.',
            'section' => 'Tinjauan Pustaka / Landasan Teori',
            'documentation' => 'uploads/laporan/draft_bab2_tinjauan_pustaka.pdf',
            'status' => 'approved',
            'dosen_note' => 'Teori yang digunakan sudah sangat lengkap.',
        ]);
        
        // 3. Metodologi / Perancangan Sistem -> Selesai (Approved)
        \App\Models\Logbook::create([
            'mahasiswa_id' => $eko->id,
            'date' => now()->subDays(6),
            'activity' => 'Membuat flowchart perancangan sistem dan rancangan database menggunakan Entity Relationship Diagram (ERD).',
            'section' => 'Metodologi / Perancangan Sistem',
            'documentation' => 'uploads/laporan/draft_bab3_metodologi.docx',
            'status' => 'approved',
            'dosen_note' => 'Rancangan ERD sudah dinormalisasi dengan baik.',
        ]);

        // 4. Analisis dan Pembahasan -> Review (Pending)
        \App\Models\Logbook::create([
            'mahasiswa_id' => $eko->id,
            'date' => now()->subDays(4),
            'activity' => 'Mengimplementasikan algoritma Collaborative Filtering menggunakan data rating 50 responden awal.',
            'section' => 'Analisis dan Pembahasan',
            'documentation' => 'uploads/laporan/draft_bab4_analisis_pembahasan.docx',
            'status' => 'pending',
        ]);

        // 5. Kesimpulan dan Saran -> Revisi (Rejected)
        \App\Models\Logbook::create([
            'mahasiswa_id' => $dewi->id,
            'date' => now()->subDays(2),
            'activity' => 'Menyusun draft kesimpulan proyek Capstone berdasarkan hasil evaluasi akurasi sistem rekomendasi.',
            'section' => 'Kesimpulan dan Saran',
            'documentation' => 'uploads/laporan/draft_bab5_kesimpulan.docx',
            'status' => 'rejected',
            'dosen_note' => 'Kesimpulan belum menjawab seluruh rumusan masalah. Silakan direvisi.',
        ]);

        // 6. Lainnya -> Review (Pending)
        \App\Models\Logbook::create([
            'mahasiswa_id' => $eko->id,
            'date' => now()->subDay(),
            'activity' => 'Menyusun daftar pustaka (menggunakan Mendeley/APA style) dan lampiran kode sumber aplikasi.',
            'section' => 'Lainnya',
            'documentation' => 'uploads/laporan/draft_lampiran.zip',
            'status' => 'pending',
        ]);

        // Create Group 3 (TI-03, pending title) - Supervised by Dosen 1 (Bambang)
        $group3 = \App\Models\Group::create([
            'group_name' => 'Kelompok Capstone TI-03',
            'theme' => 'peningkatan produktivitas pertanian perikanan perkebunan',
            'dosen_id' => $dosen->id,
            'title' => 'Rancang Bangun Sistem Smart Agriculture Berbasis IoT dan Machine Learning',
            'description' => 'Sistem cerdas untuk mendeteksi kelembaban tanah dan mengontrol irigasi tanaman pangan secara otomatis.',
            'status' => 'pending',
        ]);

        // Mahasiswa 6 (Faris - Member of Group 3)
        User::create([
            'name' => 'Faris Rahman',
            'email' => 'faris@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'nim' => '20220145',
            'prodi' => 'Teknologi Informasi',
            'angkatan' => '2022',
            'status_pkl' => 'belum',
            'group_id' => $group3->id,
        ]);

        // Mahasiswa 7 (Gita - Member of Group 3)
        User::create([
            'name' => 'Gita Amelia',
            'email' => 'gita@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'nim' => '20220146',
            'prodi' => 'Teknologi Informasi',
            'angkatan' => '2022',
            'status_pkl' => 'belum',
            'group_id' => $group3->id,
        ]);

        // Create Group 4 (TI-04, rejected title) - Supervised by Dosen 2 (Siti)
        $group4 = \App\Models\Group::create([
            'group_name' => 'Kelompok Capstone TI-04',
            'theme' => 'peningkatan layanan pendidikan formal informal',
            'dosen_id' => $dosen2->id,
            'title' => 'Sistem Kasir Sederhana Minimarket',
            'description' => 'Aplikasi kasir berbasis Java desktop untuk mencatat transaksi penjualan harian minimarket.',
            'status' => 'rejected',
        ]);

        // Mahasiswa 8 (Hendra - Member of Group 4)
        User::create([
            'name' => 'Hendra Wijaya',
            'email' => 'hendra@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'nim' => '20220147',
            'prodi' => 'Teknologi Informasi',
            'angkatan' => '2022',
            'status_pkl' => 'belum',
            'group_id' => $group4->id,
        ]);

        // Mahasiswa 9 (Indah - Member of Group 4)
        User::create([
            'name' => 'Indah Permata',
            'email' => 'indah@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'nim' => '20220148',
            'prodi' => 'Teknologi Informasi',
            'angkatan' => '2022',
            'status_pkl' => 'belum',
            'group_id' => $group4->id,
        ]);
    }
}
