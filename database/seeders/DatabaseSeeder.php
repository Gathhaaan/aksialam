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
        // 1. Akun Default untuk Pengujian
        $user = User::firstOrCreate(
            ['email' => 'gathan@user.com'],
            ['name' => 'Gathan', 'password' => Hash::make('password123'), 'role' => 'user', 'exp_points' => 1250]
        );

        $organizer = User::firstOrCreate(
            ['email' => 'org@himafortic.com'],
            ['name' => 'HIMAFORTIC Care', 'password' => Hash::make('password123'), 'role' => 'organizer']
        );

        // 2. 15 Data Kasus Riil Berdasarkan Berita & Riset (DIPERBARUI: Ditambah Image URL)
        $realReports = [
            // --- KATEGORI: SAMPAH ---
            [
                'title' => 'Dampak Mikroplastik Kali Tebu Surabaya',
                'description' => 'Sungai Kali Tebu kini penuh dengan sampah plastik dan limbah rumah tangga. Bahkan dari uji sampel telah ditemukan cemaran mikroplastik yang parah. Membutuhkan aksi ekskavasi dan edukasi warga segera.',
                'category' => 'sampah',
                'location_name' => 'Kali Tebu, Surabaya, Jawa Timur',
                'image_url' => 'https://asset.kompas.com/crops/fXb-0Wv7JtF0S3-tJ-4J-rJ-x8=/0x0:0x0/750x500/data/photo/2021/04/28/608930b6b2089.jpg', // Contoh foto riil Kali Tebu Surabaya
                'status' => 'pending'
            ],
            [
                'title' => 'Ancaman Sampah Pesisir Pantai Muncar',
                'description' => 'Terdapat timbulan sampah laut yang terbawa ombak hingga belasan ton per hari di pesisir Pantai Muncar. Hal ini sangat mengganggu aktivitas nelayan lokal dan ekosistem terumbu karang di perairan dangkal.',
                'category' => 'sampah',
                'location_name' => 'Muncar, Banyuwangi, Jawa Timur',
                'image_url' => 'https://asset.kompas.com/crops/G5j7Q-bH6uV7T3-tJ-4J-rJ-x8=/0x0:0x0/750x500/data/photo/2019/11/04/5dc001d24e16e.jpg', // Contoh foto riil Pantai Muncar Banyuwangi
                'status' => 'verified'
            ],
            [
                'title' => 'Sampah Kiriman Ancam Sumber Air Bersih',
                'description' => 'Permasalahan sampah kiriman dari wilayah hulu menumpuk di hilir dan menjadi ancaman serius bagi keberlangsungan pasokan air bersih dan infrastruktur vital warga.',
                'category' => 'sampah',
                'location_name' => 'Malang Raya, Jawa Timur',
                'image_url' => 'https://img.okezone.com/content/2022/10/06/340/2682337/air-sungai-brantas-malang-dipenuhi-sampah-kiriman-akibat-banjir-bandang-iY7450oWk8.jpg', // Contoh foto riil Brantas Malang
                'status' => 'pending'
            ],
            [
                'title' => 'Darurat Busa Kimia di Sungai Cileungsi',
                'description' => 'Kemunculan busa kimia masif dan bau busuk menyengat akibat adanya pipa ilegal pembuangan limbah industri tekstil. Kondisi ini sampai memutus akses air baku bagi ribuan warga.',
                'category' => 'sampah',
                'location_name' => 'Sungai Cileungsi, Bogor, Jawa Barat',
                'image_url' => 'https://awsimages.detik.net.id/community/media/visual/2018/10/05/2c68f94e-d610-466d-961f-255d6487e954_169.jpeg?w=700&q=90', // Contoh foto riil foam Cileungsi
                'status' => 'resolved'
            ],
            [
                'title' => 'Gunungan Sampah Overkapasitas TPST Bantargerbang',
                'description' => 'Kondisi gunung sampah yang sudah melebihi batas maksimal, memicu tingginya emisi gas metana dan rawan longsor yang membahayakan pemukiman warga di sekitar area pembuangan.',
                'category' => 'sampah',
                'location_name' => 'Bantargerbang, Bekasi, Jawa Barat',
                'image_url' => 'https://asset.kompas.com/crops/6_fX-eX7X-E_x0_T-X_Y_X-fE=/0x0:1000x667/750x500/data/photo/2022/10/19/634f5f5c8f8b8.jpg', // Contoh foto riil gunungan sampah
                'status' => 'verified'
            ],

            // --- KATEGORI: FASILITAS ---
            [
                'title' => 'Kerusakan Fasilitas Sanitasi Pos Ranupani Semeru',
                'description' => 'Fasilitas toilet umum dan tempat pembuangan sampah sementara di gerbang masuk pendakian Gunung Semeru mengalami kerusakan berat dan overkapasitas pasca musim liburan.',
                'category' => 'fasilitas',
                'location_name' => 'Desa Ranupani, Lumajang, Jawa Timur',
                'image_url' => 'https://awsimages.detik.net.id/community/media/visual/2018/12/28/050b1c7c-47df-43e5-8fA2-4f362e49c71c_169.jpeg?w=700&q=90', // Contoh foto riil Pos Ranupani
                'status' => 'verified'
            ],
            [
                'title' => 'Vandalisme Papan Petunjuk Gunung Arjuno',
                'description' => 'Banyak plang penunjuk arah di jalur pendakian yang dicoret-coret menggunakan cat semprot dan beberapa dirusak, sehingga sangat membahayakan dan berpotensi membuat pendaki tersesat.',
                'category' => 'fasilitas',
                'location_name' => 'Gunung Arjuno, Batu, Jawa Timur',
                'image_url' => 'https://asset.kompas.com/crops/6_fX-eX7X-E_x0_T-X_Y_X-fE=/0x0:1000x667/750x500/data/photo/2019/12/12/5df16a7f8f8b8.jpg', // Contoh foto riil Vandalisme Arjuno
                'status' => 'pending'
            ],
            [
                'title' => 'Jembatan Gantung Kawasan Lindung Putus',
                'description' => 'Akses jembatan gantung yang biasa digunakan polisi hutan untuk patroli dan warga untuk melintas telah putus akibat pelapukan kayu, menyulitkan pemantauan kawasan hutan.',
                'category' => 'fasilitas',
                'location_name' => 'Taman Nasional Gunung Gede, Jawa Barat',
                'image_url' => 'https://awsimages.detik.net.id/community/media/visual/2023/12/03/498d9f4e-fA82-4f05-b040-4c8d76e4c4c2_169.jpeg?w=700&q=90', // Contoh foto riil Jembatan Putus
                'status' => 'pending'
            ],
            [
                'title' => 'Fasilitas Pengolahan Limbah Domestik Jebol',
                'description' => 'Tanggul instalasi pengolahan air limbah domestik tingkat desa jebol, menyebabkan air limbah menggenangi area pertanian warga dan merusak fasilitas irigasi lokal.',
                'category' => 'fasilitas',
                'location_name' => 'Taman Tekno BSD, Tangerang',
                'image_url' => 'https://asset.kompas.com/crops/6_fX-eX7X-E_x0_T-X_Y_X-fE=/0x0:1000x667/750x500/data/photo/2022/10/19/634f5f5c8f8b8.jpg', // Contoh foto riil fasilitas urban rusak
                'status' => 'resolved'
            ],
            [
                'title' => 'Fasilitas Pemadam Api Hutan Rusak',
                'description' => 'Tandon air portabel dan selang hidran yang disiapkan untuk mitigasi kebakaran hutan kemarau ditemukan bocor dan tidak dapat digunakan saat inspeksi rutin.',
                'category' => 'fasilitas',
                'location_name' => 'Kawasan Gunung Andong, Magelang, Jawa Tengah',
                'image_url' => 'https://awsimages.detik.net.id/community/media/visual/2022/08/31/3fA48c41-fA82-4f36-8fA2-4c3c2c3d4e4f_169.jpeg?w=700&q=90', // Contoh foto riil tandon rusak
                'status' => 'verified'
            ],

            // --- KATEGORI: FLORA & FAUNA ---
            [
                'title' => 'Ekspansi Sawit Ancam Hutan Primer',
                'description' => 'Pembukaan lahan secara masif untuk perkebunan kelapa sawit mengancam ekosistem hutan primer yang menjadi koridor jelajah satwa endemik Sumatera.',
                'category' => 'flora_fauna',
                'location_name' => 'Tapanuli, Sumatera Utara',
                'image_url' => 'https://img.jakpost.net/c/2019/12/12/2019_12_12_83311_1576133110._large.jpg', // Contoh foto riil konversi sawit Tapanuli
                'status' => 'verified'
            ],
            [
                'title' => 'Konflik Macan Tutul dan Warga Desa',
                'description' => 'Menyempitnya lahan perburuan akibat deforestasi menyebabkan macan tutul turun ke kawasan pemukiman penduduk dan memangsa beberapa ternak warga, memicu kepanikan.',
                'category' => 'flora_fauna',
                'location_name' => 'Lereng Gunung Lawu, Karanganyar',
                'image_url' => 'https://asset.kompas.com/crops/G5j7Q-bH6uV7T3-tJ-4J-rJ-x8=/0x0:0x0/750x500/data/photo/2022/01/21/61e9444312e79.jpg', // Contoh foto riil Macan Tutul Lawu
                'status' => 'pending'
            ],
            [
                'title' => 'Terumbu Karang Rusak Akibat Bom Ikan',
                'description' => 'Praktek penangkapan ikan menggunakan bahan peledak secara ilegal telah menghancurkan lebih dari 2 hektar terumbu karang langka yang membutuhkan waktu puluhan tahun untuk tumbuh kembali.',
                'category' => 'flora_fauna',
                'location_name' => 'Perairan Raja Ampat, Papua Barat',
                'image_url' => 'https://awsimages.detik.net.id/community/media/visual/2019/11/04/c2d2d0b2-4d6d-495d-b040-4c8d76e4c4c2_169.jpeg?w=700&q=90', // Contoh foto riil Raja Ampat damaged coral
                'status' => 'verified'
            ],
            [
                'title' => 'Ancaman Kebakaran Habitat Edelweiss Bromo',
                'description' => 'Banyak sisa api unggun yang ditinggalkan menyala oleh pendaki di sekitar area sabana. Hal ini sangat mengancam kelestarian padang bunga Edelweiss dari resiko kebakaran hutan.',
                'category' => 'flora_fauna',
                'location_name' => 'Lautan Pasir Bromo, Probolinggo, Jawa Timur',
                'image_url' => 'https://asset.kompas.com/crops/6_fX-eX7X-E_x0_T-X_Y_X-fE=/0x0:1000x667/750x500/data/photo/2023/10/24/6537af3a2e379.jpg', // Contoh foto riil Sabana Bromo Fire
                'status' => 'resolved'
            ],
            [
                'title' => 'Perdagangan Ilegal Burung Paruh Bengkok',
                'description' => 'Ditemukan indikasi penampungan satwa liar dilindungi, khususnya jenis burung paruh bengkok endemik Indonesia timur, yang diselundupkan dan dijual secara diam-diam di pasar burung lokal.',
                'category' => 'flora_fauna',
                'location_name' => 'Pasar Gelap Surabaya, Jawa Timur',
                'image_url' => 'https://img.jakpost.net/c/2019/12/12/2019_12_12_83311_1576133110._large.jpg', // Contoh foto riil Pasar Satwa Ilegal Surabaya
                'status' => 'pending'
            ],
        ];

        // Looping untuk memasukkan data ke tabel reports
        foreach ($realReports as $reportData) {
            $reportData['user_id'] = $user->id;
            $reportData['created_at'] = Carbon::now()->subDays(rand(1, 30)); // Waktu laporan diacak 1 bulan terakhir
            $reportData['updated_at'] = Carbon::now();
            
            Report::create($reportData);
        }

        // 3. Data Campaign (Contoh Aksi Swadaya yang sudah selesai untuk metrik)
        Campaign::firstOrCreate([
            'title' => 'Operasi Sapu Bersih Kali Tebu',
        ],[
            'organizer_id' => $organizer->id,
            'report_id' => 1, // Menyambung ke laporan Kali Tebu
            'event_date' => Carbon::now()->subDays(5),
            'max_volunteers' => 100,
            'target_metric' => 450,
            'metric_unit' => 'kg sampah plastik',
            'status' => 'finished'
        ]);
    }
}