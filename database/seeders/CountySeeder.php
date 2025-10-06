<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\County;

class CountySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('seeders/data/counties.csv');
        if (!file_exists($filePath)) {
            $this->command->error('File not found: $filePath');
            return;
        }
        $handle = fopen($filePath, 'r');
        while (($data = fgetcsv($handle, 1000, ';')) !==false) {
            $countyName = trim($data[0]);
            if ($countyName) {
                County::firstOrCreate(['name'=>$countyName]);
            }
        }
        fclose($handle);
        $this->command->info('Counties imported successfully!');
    }
}
