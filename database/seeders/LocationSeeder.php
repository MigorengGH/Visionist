<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            'Johor' => ['Batu Pahat', 'Johor Bahru', 'Kluang', 'Kota Tinggi', 'Kulai', 'Mersing', 'Muar', 'Pontian', 'Segamat', 'Tangkak'],
            'Kedah' => ['Baling', 'Bandar Baharu', 'Kota Setar', 'Kuala Muda', 'Kubang Pasu', 'Langkawi', 'Padang Terap', 'Pendang', 'Pokok Sena', 'Sik', 'Yan'],
            'Kelantan' => ['Bachok', 'Gua Musang', 'Jeli', 'Kota Bharu', 'Kuala Krai', 'Machang', 'Pasir Mas', 'Pasir Puteh', 'Tanah Merah', 'Tumpat'],
            'Melaka' => ['Alor Gajah', 'Jasin', 'Melaka Tengah'],
            'Negeri Sembilan' => ['Jelebu', 'Jempol', 'Kuala Pilah', 'Port Dickson', 'Rembau', 'Seremban', 'Tampin'],
            'Pahang' => ['Bentong', 'Bera', 'Cameron Highlands', 'Jerantut', 'Kuantan', 'Lipis', 'Maran', 'Pekan', 'Raub', 'Rompin', 'Temerloh'],
            'Perak' => ['Bagan Datuk', 'Batu Gajah', 'Hilir Perak', 'Hulu Perak', 'Ipoh', 'Kampar', 'Kuala Kangsar', 'Larut, Matang & Selama', 'Manjung', 'Muallim', 'Perak Tengah', 'Selama', 'Taiping', 'Teluk Intan'],
            'Perlis' => ['Kangar', 'Arau', 'Padang Besar'],
            'Pulau Pinang' => ['Barat Daya', 'Timur Laut', 'Seberang Perai Tengah', 'Seberang Perai Selatan', 'Seberang Perai Utara'],
            'Sabah' => ['Beaufort', 'Beluran', 'Keningau', 'Kota Belud', 'Kota Kinabalu', 'Kota Marudu', 'Kuala Penyu', 'Kudat', 'Kunak', 'Lahad Datu', 'Nabawan', 'Papar', 'Penampang', 'Putatan', 'Ranau', 'Sandakan', 'Semporna', 'Sipitang', 'Tambunan', 'Tawau', 'Tenom', 'Tongod'],
            'Sarawak' => ['Betong', 'Bintulu', 'Kapit', 'Kuching', 'Limbang', 'Miri', 'Mukah', 'Samarahan', 'Sarikei', 'Serian', 'Sibu'],
            'Selangor' => ['Gombak', 'Hulu Langat', 'Hulu Selangor', 'Klang', 'Kuala Langat', 'Kuala Selangor', 'Petaling', 'Sabak Bernam', 'Sepang'],
            'Terengganu' => ['Besut', 'Dungun', 'Hulu Terengganu', 'Kemaman', 'Kuala Terengganu', 'Marang', 'Setiu'],
            'Wilayah Persekutuan' => ['Kuala Lumpur', 'Labuan', 'Putrajaya']
        ];

        $data = [];
        foreach ($locations as $state => $districts) {
            foreach ($districts as $district) {
                $data[] = ['state' => $state, 'district' => $district];
            }
        }

        DB::table('locations')->insert($data);
    }
}
