<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\City;
use App\Models\State;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['state' => 'Uttar Pradesh', 'cities' => [
                'Lucknow', 'Kanpur', 'Varanasi', 'Agra', 'Allahabad', 'Ghaziabad',
                'Noida', 'Meerut', 'Aligarh', 'Moradabad', 'Saharanpur', 'Gorakhpur',
                'Bareilly', 'Jhansi', 'Firozabad', 'Muzaffarnagar', 'Mathura',
                'Ayodhya', 'Rampur', 'Shahjahanpur', 'Ballia', 'Faizabad', 'Barabanki',
                'Fatehpur', 'Etawah', 'Sitapur', 'Jaunpur', 'Raebareli', 'Banda',
                'Sultanpur', 'Deoria', 'Azamgarh', 'Unnao', 'Basti', 'Bijnor',
                'Mainpuri', 'Gonda', 'Hathras', 'Bulandshahr', 'Amroha', 'Etah',
                'Pilibhit', 'Chandauli', 'Hardoi', 'Lakhimpur', 'Bahraich', 'Balrampur',
                'Ghazipur', 'Amethi', 'Ambedkar Nagar', 'Kasganj', 'Sambhal', 'Mirzapur',
                'Mau', 'Shamli', 'Kannauj', 'Lalitpur', 'Kheri', 'Sant Kabir Nagar',
                'Bhadohi', 'Chitrakoot', 'Hapur', 'Farrukhabad', 'Auraiya', 'Kaushambi',
                'Budaun', 'Pratapgarh', 'Mahoba', 'Hamirpur', 'Shrawasti',
                'Siddharthnagar', 'Sonbhadra'
            ]],
            ['state' => 'Delhi', 'cities' => [
                'Central Delhi', 'East Delhi', 'New Delhi', 'North Delhi', 'North East Delhi',
                'North West Delhi', 'South Delhi', 'South East Delhi', 'South West Delhi',
                'West Delhi', 'Shahdara', 'Dwarka', 'Rohini', 'Janakpuri', 'Vasant Kunj',
                'Saket', 'Lajpat Nagar', 'Karol Bagh', 'Chanakyapuri', 'Okhla', 'Mayur Vihar'
            ]],
            ['state' => 'Bihar', 'cities' => [
                'Patna','Gaya','Bhagalpur','Muzaffarpur','Darbhanga','Purnia','Ara','Begusarai','Katihar',
                'Munger','Chhapra','Bihar Sharif','Motihari','Samastipur','Saharsa','Hajipur','Siwan',
                'Dehri','Bettiah','Jamalpur','Buxar','Sitamarhi','Araria','Sasaram','Kishanganj',
                'Jehanabad','Lakhisarai','Nawada','Madhepura','Bhabua','Khagaria','Sheikhpura',
                'Sheohar','Aurangabad','Jamui','Arwal','Banka'
            ]],
        ];

        foreach ($cities as $entry) {
            $state = State::where('state_name', $entry['state'])->first();
            if ($state) {
                foreach ($entry['cities'] as $cityName) {
                    City::create([
                        'state_id' => $state->id,
                        'city_name' => $cityName
                    ]);
                }
            }
        }
    }
}
