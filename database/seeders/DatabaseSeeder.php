<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Report;
use App\Models\Campaign;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Utama Gathan (Informatika UNESA)
        $user = User::firstOrCreate(
            ['email' => 'gathan@user.com'],
            ['name' => 'Gathan', 'password' => Hash::make('password123'), 'role' => 'user', 'exp_points' => 1250]
        );

        $organizer = User::firstOrCreate(
            ['email' => 'org@himafortic.com'],
            ['name' => 'HIMAFORTIC Care', 'password' => Hash::make('password123'), 'role' => 'organizer']
        );

        // Akun Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@aksialam.com'],
            ['name' => 'Super Admin AksiAlam', 'password' => Hash::make('password123'), 'role' => 'admin', 'exp_points' => 0]
        );

        $realReports = [
            // --- KATEGORI: SAMPAH ---
            [
                'title' => 'Dampak Mikroplastik Kali Tebu Surabaya',
                'description' => 'Sungai Kali Tebu kini penuh dengan sampah plastik yang mencemari ekosistem air warga.',
                'category' => 'sampah', 'location_name' => 'Kali Tebu, Surabaya, Jawa Timur', 'status' => 'pending',
                'image_url' => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?q=80&w=1000'
            ],
            [
                'title' => 'Ancaman Sampah Pesisir Pantai Muncar',
                'description' => 'Timbulan sampah laut yang sangat parah di pesisir pantai Banyuwangi.',
                'category' => 'sampah', 'location_name' => 'Muncar, Banyuwangi, Jawa Timur', 'status' => 'verified',
                'image_url' => 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?q=80&w=1000'
            ],
            [
                'title' => 'Sampah Kiriman Ancaman Air Bersih',
                'description' => 'Permasalahan sampah kiriman dari hulu yang menumpuk di hilir sungai.',
                'category' => 'sampah', 'location_name' => 'Malang Raya, Jawa Timur', 'status' => 'pending',
                'image_url' => 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?q=80&w=1000'
            ],
            [
                'title' => 'Darurat Busa Kimia Sungai Cileungsi',
                'description' => 'Kemunculan busa kimia masif akibat limbah industri yang dibuang secara ilegal.',
                'category' => 'sampah', 'location_name' => 'Sungai Cileungsi, Bogor', 'status' => 'resolved',
                'image_url' => 'https://images.unsplash.com/photo-1596464716127-f2a82984de30?q=80&w=1000'
            ],
            [
                'title' => 'Gunungan Sampah Overkapasitas TPST',
                'description' => 'Kondisi gunung sampah yang sudah melebihi batas maksimal dan membahayakan warga.',
                'category' => 'sampah', 'location_name' => 'Bantargerbang, Bekasi', 'status' => 'verified',
                'image_url' => 'https://images.unsplash.com/photo-1516992654410-9309d4587e94?q=80&w=1000'
            ],

            // --- KATEGORI: FASILITAS ---
            [
                'title' => 'Kerusakan Sanitasi Pos Ranupani Semeru',
                'description' => 'Fasilitas toilet umum di gerbang pendakian Semeru mengalami kerusakan berat.',
                'category' => 'fasilitas', 'location_name' => 'Desa Ranupani, Lumajang', 'status' => 'verified',
                'image_url' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?q=80&w=1000'
            ],
            [
                'title' => 'Vandalisme Papan Petunjuk Arjuno',
                'description' => 'Banyak plang penunjuk arah di jalur pendakian yang dicoret-coret oknum.',
                'category' => 'fasilitas', 'location_name' => 'Gunung Arjuno, Batu', 'status' => 'pending',
                'image_url' => 'https://images.unsplash.com/photo-1501555088652-021faa106b9b?q=80&w=1000'
            ],
            [
                'title' => 'Jembatan Patroli Hutan Putus',
                'description' => 'Akses jembatan gantung untuk patroli polisi hutan terputus akibat pelapukan.',
                'category' => 'fasilitas', 'location_name' => 'TN Gunung Gede Pangrango', 'status' => 'pending',
                'image_url' => 'https://images.unsplash.com/photo-1444491741275-3747c53c99b4?q=80&w=1000'
            ],

            // --- KATEGORI: FLORA & FAUNA ---
            [
                'title' => 'Ekspansi Sawit Ancam Hutan Primer',
                'description' => 'Pembukaan lahan secara masif mengancam habitat satwa endemik Sumatera.',
                'category' => 'flora_fauna', 'location_name' => 'Tapanuli, Sumatera Utara', 'status' => 'verified',
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=1000'
            ],
            [
                'title' => 'Konflik Macan Tutul dan Warga Desa',
                'description' => 'Macan tutul mulai memasuki pemukiman warga akibat rusaknya lahan hutan.',
                'category' => 'flora_fauna', 'location_name' => 'Lereng Gunung Lawu', 'status' => 'pending',
                'image_url' => 'https://images.unsplash.com/photo-1456926631375-92c8ce872def?q=80&w=1000'
            ],
            [
                'title' => 'Kerusakan Terumbu Karang Raja Ampat',
                'description' => 'Praktek bom ikan telah menghancurkan ekosistem bawah laut yang sangat vital.',
                'category' => 'flora_fauna', 'location_name' => 'Perairan Papua Barat', 'status' => 'verified',
                'image_url' => 'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?q=80&w=1000'
            ],
            [
                'title' => 'Ancaman Kebakaran Habitat Edelweiss',
                'description' => 'Sisa api unggun pendaki mengancam kelestarian bunga abadi di sabana.',
                'category' => 'flora_fauna', 'location_name' => 'Lautan Pasir Bromo', 'status' => 'resolved',
                'image_url' => 'https://images.unsplash.com/photo-1448375240586-882707db888b?q=80&w=1000'
            ],
            [
                'title' => 'Perdagangan Ilegal Burung Dilindungi',
                'description' => 'Ditemukan indikasi penampungan satwa liar yang diselundupkan secara diam-diam.',
                'category' => 'flora_fauna', 'location_name' => 'Surabaya, Jawa Timur', 'status' => 'pending',
                'image_url' => 'https://images.unsplash.com/photo-1522069169874-c58ec4b76be5?q=80&w=1000'
            ],
        ];

        foreach ($realReports as $data) {
            $data['user_id'] = $user->id;
            Report::create($data);
        }

        Campaign::create([
            'title' => 'Sapu Bersih Kali Tebu', 'organizer_id' => $organizer->id, 'report_id' => 1,
            'event_date' => Carbon::now()->addDays(7), 'max_volunteers' => 50,
            'target_metric' => 280, 'metric_unit' => 'kg plastik', 'status' => 'finished'
        ]);
    }
}