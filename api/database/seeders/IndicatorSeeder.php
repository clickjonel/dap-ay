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
        $indicators = [
            [
                'name' => 'Total number of household provided with PK services',
                'type' => 'Priority',
                'sub_program_id' => 3,
                'active' => true,
                'disaggregations' => []
            ],
            [
                'name' => 'Total number of individuals provided with PK services',
                'type' => 'Target',
                'sub_program_id' => null,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Clients reached for Nutrition',
                'type' => 'Priority',
                'sub_program_id' => 1,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Clients reached for Immunization',
                'type' => 'Priority',
                'sub_program_id' => 2,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Clients reached for Maternal Health',
                'type' => 'Priority',
                'sub_program_id' => 7,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Clients reached for TB',
                'type' => 'Priority',
                'sub_program_id' => 4,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Clients reached for HIV',
                'type' => 'Priority',
                'sub_program_id' => 5,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Clients reached for Road Safety',
                'type' => 'Priority',
                'sub_program_id' => 6,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Clients reached for NCDs Diabetes',
                'type' => 'Priority',
                'sub_program_id' => 8,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Clients reached for NCDs Hypertension',
                'type' => 'Priority',
                'sub_program_id' => 9,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Clients reached for Breast Cancer',
                'type' => 'Priority',
                'sub_program_id' => 10,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Clients reached for Cervical Cancer',
                'type' => 'Priority',
                'sub_program_id' => 11,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Individuals Reached through Health Promotion',
                'type' => 'Priority',
                'sub_program_id' => 13,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Individuals Provided with FPE',
                'type' => 'Target',
                'sub_program_id' => null,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Individuals registered to Philhealth',
                'type' => 'Target',
                'sub_program_id' => null,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Individuals provided with advocacies on FPE or Philhealh',
                'type' => 'Target',
                'sub_program_id' => null,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Individuals Reffered to higher facility',
                'type' => 'Target',
                'sub_program_id' => null,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],
            [
                'name' => 'Number of Large Scale Activities Conducted',
                'type' => 'Target',
                'sub_program_id' => null,
                'active' => true,
                'disaggregations' => [
                    [
                        'name' => 'Male',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => 'Female',
                        'totalable' => true,
                        'active' => true
                    ],
                    [
                        'name' => '4Ps Benificiary',
                        'totalable' => false,
                        'active' => true
                    ],
                    [
                        'name' => 'Not Identified',
                        'totalable' => false,
                        'active' => true
                    ],
                ]
            ],

        ];

        foreach($indicators as $ind){
             $indicator = Indicator::create([
                'name' => $ind['name'],
                'type' => $ind['type'],
                'sub_program_id' => $ind['sub_program_id'],
                'active' => $ind['active'],
            ]);

            if (!empty($ind['disaggregations'])) {
                $indicator->disaggregations()->createMany($ind['disaggregations']);
            }
        }
    }
}
