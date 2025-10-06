<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('seeders/data/postalcodes.csv');
        if (!file_exists($filePath)) {
            $this->command->error('File not found: $filePath');
            return;
        }
        $handle = fopen($filePath, 'r');

        $length = count(file($filePath));
        $this->command->getOutput()->progressStart($length);

        while (($data = fgetcsv($handle, 1000, ';')) !==false) {
            $code = trim($data[0]);
            $name = trim($data[1]);
            $countyId = trim($data[2]);
            if ($countyId=='') {
                $countyId = null;
            }
            else{
                $countyId = (int)$countyId;
            }
            if ($code || $name) {
                City::firstOrCreate([
                    'postal_code'=>(int)$code,
                    'place_name'=>$name,
                    'county_id'=>$countyId
            ]);
            }
            $this->command->getOutput()->progressAdvance();
        }
        fclose($handle);
        $this->command->getOutput()->progressFinish();
        $this->command->info('Counties imported successfully!');
    }
}
