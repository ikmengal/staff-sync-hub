<?php

namespace Database\Seeders;

use App\Models\EstimateStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstimateStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("estimate_statuses")->truncate();
        $array = [
            ['name' => 'Pending', 'class' => 'warning'],
            ['name' => 'Approved', 'class' => 'success'],
            ['name' => 'Rejected', 'class' => 'danger'],
        ];
        foreach ($array as $value) {
            EstimateStatus::create([
                "name" => $value["name"] ?? null,
                "class" => $value["class"] ?? null,
            ]);
        }
    }
}
