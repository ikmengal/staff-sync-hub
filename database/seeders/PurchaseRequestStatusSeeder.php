<?php

namespace Database\Seeders;

use App\Models\PurchaseRequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseRequestStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("purchase_request_statuses")->truncate();
        $array = [
            ['name' => 'pending', 'class' => 'warning'],
            ['name' => 'approved', 'class' => 'success'],
            ['name' => 'rejected', 'class' => 'danger'],
        ];
        foreach ($array as $value) {
            PurchaseRequestStatus::create([
                "name" => $value["name"] ?? null,
                "class" => $value["class"] ?? null,
            ]);
        }
    }
}
