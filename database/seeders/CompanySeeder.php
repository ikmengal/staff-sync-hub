<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("companies")->truncate();
        $array = [

            ["company_id" => 1, "base_url" => "", "name" => "Cyberonix Consulting Limited"],
            ["company_id" => 2, "base_url" => "", "name" => "Vertical Edge"],
            ["company_id" => 3, "base_url" => "", "name" => "Braincell  Technology"],
            ["company_id" => 4, "base_url" => "", "name" => "C-Level"],
            ["company_id" => 5, "base_url" => "", "name" => "DELVE12"],
            ["company_id" => 6, "base_url" => "", "name" => "HORIZONTAL"],
            ["company_id" => 7, "base_url" => "", "name" => "MERCURY"],
            ["company_id" => 8, "base_url" => "", "name" => "MOMYOM"],
            ["company_id" => 9, "base_url" => "", "name" => "SOFTNOVA"],
            ["company_id" => 10, "base_url" => "", "name" => "SOFTFELLOW"],
            ["company_id" => 11, "base_url" => "", "name" => "SWYFTCUBE"],
            ["company_id" => 12, "base_url" => "", "name" => "SWYFTZONE"],
            ["company_id" => 13, "base_url" => "", "name" => "TECHCOMRADE"],
            ["company_id" => 14, "base_url" => "", "name" => "ROCKET-FLARE-LABS"],

        ];
        foreach ($array as $value) {
            Company::create([
                "company_id" => $value['company_id'] ?? 0,
                "name" => $value['name'] ?? 0,
            ]);
        }
    }
}
