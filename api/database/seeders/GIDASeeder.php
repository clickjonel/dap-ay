<?php

namespace Database\Seeders;

use App\Models\Barangay;
use Illuminate\Database\Seeder;

class GIDASeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('app/UUA_2025.csv');
        $file = fopen($path, 'r');
        
        $headers = fgetcsv($file); // Get headers
        $rows = [];
        
        while (($row = fgetcsv($file)) !== false) {
            $rows[] = array_combine($headers, $row);
        }
        
        fclose($file);
        
        foreach($rows as $index => $row){
            $barangay = Barangay::find((int)$row['barangay_id'])->update([
                'is_gida' => true,
                'deployed_hrh' => (int)$row['deployed_hrh']
            ]);
        }
        
    }
}
