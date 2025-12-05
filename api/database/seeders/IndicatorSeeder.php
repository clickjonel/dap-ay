<?php

namespace Database\Seeders;

use App\Models\Indicator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('app/indicators.csv');
        $file = fopen($path, 'r');
        
        $headers = fgetcsv($file); // Get headers
        $rows = [];
        
        while (($row = fgetcsv($file)) !== false) {
            $rows[] = array_combine($headers, $row);
        }
        
        fclose($file);
        
        foreach($rows as $index => $row){
            Indicator::create([
                'name' => $row['name'],
                'sub_program_id' => $row['sub_program_id'] === 'null' ? null : intval($row['sub_program_id']),
                'active' => true,
            ]);
        }

    }
}
