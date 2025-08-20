<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StateSeeder extends Seeder
{
    public function run()
    {
        $states = [
            ['state_name' => 'Andhra Pradesh', 'state_code' => 'AP'],
            ['state_name' => 'Arunachal Pradesh', 'state_code' => 'AR'],
            ['state_name' => 'Assam', 'state_code' => 'AS'],
            ['state_name' => 'Bihar', 'state_code' => 'BR'],
            ['state_name' => 'Chhattisgarh', 'state_code' => 'CG'],
            ['state_name' => 'Goa', 'state_code' => 'GA'],
            ['state_name' => 'Gujarat', 'state_code' => 'GJ'],
            ['state_name' => 'Haryana', 'state_code' => 'HR'],
            ['state_name' => 'Himachal Pradesh', 'state_code' => 'HP'],
            ['state_name' => 'Jharkhand', 'state_code' => 'JH'],
            ['state_name' => 'Karnataka', 'state_code' => 'KA'],
            ['state_name' => 'Kerala', 'state_code' => 'KL'],
            ['state_name' => 'Madhya Pradesh', 'state_code' => 'MP'],
            ['state_name' => 'Maharashtra', 'state_code' => 'MH'],
            ['state_name' => 'Manipur', 'state_code' => 'MN'],
            ['state_name' => 'Meghalaya', 'state_code' => 'ML'],
            ['state_name' => 'Mizoram', 'state_code' => 'MZ'],
            ['state_name' => 'Nagaland', 'state_code' => 'NL'],
            ['state_name' => 'Odisha', 'state_code' => 'OR'],
            ['state_name' => 'Punjab', 'state_code' => 'PB'],
            ['state_name' => 'Rajasthan', 'state_code' => 'RJ'],
            ['state_name' => 'Sikkim', 'state_code' => 'SK'],
            ['state_name' => 'Tamil Nadu', 'state_code' => 'TN'],
            ['state_name' => 'Telangana', 'state_code' => 'TG'],
            ['state_name' => 'Tripura', 'state_code' => 'TR'],
            ['state_name' => 'Uttar Pradesh', 'state_code' => 'UP'],
            ['state_name' => 'Uttarakhand', 'state_code' => 'UK'],
            ['state_name' => 'West Bengal', 'state_code' => 'WB'],

            // Union Territories
            ['state_name' => 'Andaman and Nicobar Islands', 'state_code' => 'AN'],
            ['state_name' => 'Chandigarh', 'state_code' => 'CH'],
            ['state_name' => 'Dadra and Nagar Haveli and Daman and Diu', 'state_code' => 'DN'],
            ['state_name' => 'Delhi', 'state_code' => 'DL'],
            ['state_name' => 'Jammu and Kashmir', 'state_code' => 'JK'],
            ['state_name' => 'Ladakh', 'state_code' => 'LA'],
            ['state_name' => 'Lakshadweep', 'state_code' => 'LD'],
            ['state_name' => 'Puducherry', 'state_code' => 'PY'],
        ];

        foreach ($states as &$state) {
            $state['country_code'] = 'IN';
            $state['is_active'] = true;
            $state['created_at'] = Carbon::now();
            $state['updated_at'] = Carbon::now();
        }

        DB::table('states')->insert($states);
    }
}
