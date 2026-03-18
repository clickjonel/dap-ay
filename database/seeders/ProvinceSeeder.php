<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
    set_time_limit(0); // High-volume API calls need time

    $regionCode = '140000000'; 
    $url = "https://psgc.gitlab.io/api/regions/{$regionCode}/provinces.json";
    $response = Http::get($url);

    if (!$response->successful()) {
        $this->command->error("Could not fetch provinces.");
        return;
    }

    $provincesData = $response->json();

    DB::transaction(function () use ($provincesData) {
        foreach ($provincesData as $provinceData) {
            // 1. Create Province
            $province = Province::create([
                'psgc_code' => $provinceData['psgc10DigitCode'],
                'name'      => $provinceData['name'],
            ]);

            $this->command->info("Seeding: {$province->name}");

            // 2. Fetch Municipalities/Cities for this Province
            $munUrl = "https://psgc.gitlab.io/api/provinces/{$provinceData['code']}/cities-municipalities.json";
            $munResponse = Http::get($munUrl);

            if ($munResponse->successful()) {
                foreach ($munResponse->json() as $munData) {
                    $municipality = Municipality::create([
                        'province_id' => $province->id,
                        'psgc_code'   => $munData['psgc10DigitCode'],
                        'name'        => $munData['name'],
                    ]);

                    // 3. Fetch Barangays for this Municipality
                    $brgyUrl = "https://psgc.gitlab.io/api/cities-municipalities/{$munData['code']}/barangays.json";
                    $brgyResponse = Http::get($brgyUrl);

                    if ($brgyResponse->successful()) {
                        $barangayBatch = collect($brgyResponse->json())->map(function ($brgy) use ($province, $municipality) {
                            return [
                                'province_id'     => $province->id,
                                'municipality_id' => $municipality->id,
                                'psgc_code'       => $brgy['psgc10DigitCode'],
                                'name'            => $brgy['name'],
                            ];
                        })->toArray();

                        // Mass insert barangays for this specific municipality
                        Barangay::insert($barangayBatch);
                    }
                }
            }
        }
    });

    $this->command->info("Finished seeding CAR region!");
}
}
