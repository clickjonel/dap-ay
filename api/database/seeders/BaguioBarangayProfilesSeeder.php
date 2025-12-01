<?php

namespace Database\Seeders;

use App\Models\Barangay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BaguioBarangayProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangays = array(
            array('id' => 313, 'total_purok' => 8, 'target_purok' => 8, 'target_population' => 14583),
            array('id' => 372, 'total_purok' => 2, 'target_purok' => 2, 'target_population' => 377),
            array('id' => 315, 'total_purok' => 12, 'target_purok' => 12, 'target_population' => 13809),
            array('id' => 316, 'total_purok' => 12, 'target_purok' => 12, 'target_population' => 11471),
            array('id' => 318, 'total_purok' => 6, 'target_purok' => 6, 'target_population' => 2619),
            array('id' => 320, 'total_purok' => 4, 'target_purok' => 4, 'target_population' => 1141),
            array('id' => 325, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 14643),
            array('id' => 324, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 2453),
            array('id' => 330, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 1864),
            array('id' => 334, 'total_purok' => 9, 'target_purok' => 9, 'target_population' => 1986),
            array('id' => 416, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 5084),
            array('id' => 337, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 8423),
            array('id' => 425, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 3041),
            array('id' => 341, 'total_purok' => 6, 'target_purok' => 6, 'target_population' => 8491),
            array('id' => 344, 'total_purok' => 5, 'target_purok' => 5, 'target_population' => 1772),
            array('id' => 317, 'total_purok' => 4, 'target_purok' => 4, 'target_population' => 2811),
            array('id' => 420, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 1805),
            array('id' => 346, 'total_purok' => 6, 'target_purok' => 6, 'target_population' => 3084),
            array('id' => 349, 'total_purok' => 32, 'target_purok' => 32, 'target_population' => 38569),
            array('id' => 351, 'total_purok' => 8, 'target_purok' => 8, 'target_population' => 6719),
            array('id' => 426, 'total_purok' => 6, 'target_purok' => 6, 'target_population' => 5098),
            array('id' => 353, 'total_purok' => 8, 'target_purok' => 8, 'target_population' => 12662),
            array('id' => 361, 'total_purok' => 4, 'target_purok' => 4, 'target_population' => 2326),
            array('id' => 434, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 3763),
            array('id' => 367, 'total_purok' => 6, 'target_purok' => 6, 'target_population' => 1404),
            array('id' => 369, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 2597),
            array('id' => 381, 'total_purok' => 3, 'target_purok' => 3, 'target_population' => 1690),
            array('id' => 374, 'total_purok' => 4, 'target_purok' => 4, 'target_population' => 2361),
            array('id' => 375, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 7396),
            array('id' => 429, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 3003),
            array('id' => 378, 'total_purok' => 10, 'target_purok' => 10, 'target_population' => 8823),
            array('id' => 379, 'total_purok' => 4, 'target_purok' => 4, 'target_population' => 3014),
            array('id' => 335, 'total_purok' => 5, 'target_purok' => 5, 'target_population' => 3691),
            array('id' => 366, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 2926),
            array('id' => 396, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 4169),
            array('id' => 385, 'total_purok' => 8, 'target_purok' => 8, 'target_population' => 9478),
            array('id' => 386, 'total_purok' => 3, 'target_purok' => 3, 'target_population' => 1015),
            array('id' => 387, 'total_purok' => 5, 'target_purok' => 5, 'target_population' => 5607),
            array('id' => 373, 'total_purok' => 3, 'target_purok' => 3, 'target_population' => 2774),
            array('id' => 436, 'total_purok' => 11, 'target_purok' => 11, 'target_population' => 2530),
            array('id' => 400, 'total_purok' => 7, 'target_purok' => 7, 'target_population' => 3027)
        );

        foreach($barangays as $brgy){
            Barangay::find($brgy['id'])->update([
                'total_purok' => $brgy['total_purok'],
                'target_purok' => $brgy['target_purok'],
                'target_population' => $brgy['target_population']
            ]);
        }
    }
}
