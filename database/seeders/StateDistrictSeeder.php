<?php

namespace Database\Seeders;

use App\Models\State;
use App\Models\District;
use Illuminate\Database\Seeder;

class StateDistrictSeeder extends Seeder
{
    public function run(): void
    {
        // Sample Malaysian states and their districts
        $states = [
            'Johor' => [
                'Johor Bahru', 'Kota Tinggi', 'Muar', 'Batu Pahat', 'Segamat',
                'Kluang', 'Pontian', 'Kulai', 'Mersing', 'Tangkak'
            ],
            'Kedah' => [
                'Alor Setar', 'Kuala Muda', 'Kubang Pasu', 'Langkawi', 'Padang Terap',
                'Pendang', 'Pokok Sena', 'Sik', 'Yan'
            ],
            'Kelantan' => [
                'Kota Bharu', 'Bachok', 'Pasir Mas', 'Tumpat', 'Pasir Puteh',
                'Tanah Merah', 'Machang', 'Jeli', 'Kuala Krai', 'Gua Musang'
            ],
            'Melaka' => [
                'Melaka Tengah', 'Alor Gajah', 'Jasin'
            ],
            'Negeri Sembilan' => [
                'Seremban', 'Port Dickson', 'Tampin', 'Kuala Pilah', 'Jelebu',
                'Rembau', 'Jempol'
            ],
            'Pahang' => [
                'Kuantan', 'Pekan', 'Rompin', 'Maran', 'Bentong',
                'Raub', 'Jerantut', 'Lipis', 'Cameron Highlands', 'Bera'
            ],
            'Perak' => [
                'Ipoh', 'Kinta', 'Larut, Matang dan Selama', 'Hilir Perak',
                'Manjung', 'Batang Padang', 'Kampar', 'Perak Tengah', 'Kuala Kangsar',
                'Hulu Perak'
            ],
            'Perlis' => [
                'Kangar', 'Arau'
            ],
            'Pulau Pinang' => [
                'Timur Laut', 'Barat Daya', 'Seberang Perai Utara',
                'Seberang Perai Tengah', 'Seberang Perai Selatan'
            ],
            'Sabah' => [
                'Kota Kinabalu', 'Penampang', 'Papar', 'Kota Belud', 'Tuaran',
                'Ranau', 'Kudat', 'Sandakan', 'Tawau', 'Lahad Datu'
            ],
            'Sarawak' => [
                'Kuching', 'Samarahan', 'Sri Aman', 'Betong', 'Sarikei',
                'Sibu', 'Mukah', 'Bintulu', 'Miri', 'Limbang'
            ],
            'Selangor' => [
                'Petaling', 'Hulu Langat', 'Gombak', 'Klang', 'Kuala Langat',
                'Sepang', 'Hulu Selangor', 'Sabak Bernam'
            ],
            'Terengganu' => [
                'Kuala Terengganu', 'Besut', 'Dungun', 'Hulu Terengganu',
                'Kemaman', 'Marang', 'Setiu'
            ],
            'Kuala Lumpur' => [
                'Kepong', 'Batu', 'Wangsa Maju', 'Titiwangsa', 'Setiawangsa',
                'Lembah Pantai', 'Seputeh', 'Cheras', 'Bandar Tun Razak'
            ],
            'Labuan' => [
                'Labuan'
            ],
            'Putrajaya' => [
                'Putrajaya'
            ]
        ];

        foreach ($states as $stateName => $districts) {
            $state = State::create(['name' => $stateName]);

            foreach ($districts as $districtName) {
                District::create([
                    'state_id' => $state->id,
                    'name' => $districtName
                ]);
            }
        }
    }
}
