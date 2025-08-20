<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = [
            ['country_name' => 'Afghanistan', 'country_code' => 'AF', 'currency' => 'AFN', 'currency_symbol' => '؋'],
            ['country_name' => 'Albania', 'country_code' => 'AL', 'currency' => 'ALL', 'currency_symbol' => 'L'],
            ['country_name' => 'Algeria', 'country_code' => 'DZ', 'currency' => 'DZD', 'currency_symbol' => 'دج'],
            ['country_name' => 'Andorra', 'country_code' => 'AD', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'Angola', 'country_code' => 'AO', 'currency' => 'AOA', 'currency_symbol' => 'Kz'],
            ['country_name' => 'Argentina', 'country_code' => 'AR', 'currency' => 'ARS', 'currency_symbol' => '$'],
            ['country_name' => 'Armenia', 'country_code' => 'AM', 'currency' => 'AMD', 'currency_symbol' => '֏'],
            ['country_name' => 'Australia', 'country_code' => 'AU', 'currency' => 'AUD', 'currency_symbol' => '$'],
            ['country_name' => 'Austria', 'country_code' => 'AT', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'Azerbaijan', 'country_code' => 'AZ', 'currency' => 'AZN', 'currency_symbol' => '₼'],
            ['country_name' => 'Bahamas', 'country_code' => 'BS', 'currency' => 'BSD', 'currency_symbol' => '$'],
            ['country_name' => 'Bahrain', 'country_code' => 'BH', 'currency' => 'BHD', 'currency_symbol' => 'ب.د'],
            ['country_name' => 'Bangladesh', 'country_code' => 'BD', 'currency' => 'BDT', 'currency_symbol' => '৳'],
            ['country_name' => 'Belgium', 'country_code' => 'BE', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'Bhutan', 'country_code' => 'BT', 'currency' => 'BTN', 'currency_symbol' => 'Nu.'],
            ['country_name' => 'Bolivia', 'country_code' => 'BO', 'currency' => 'BOB', 'currency_symbol' => 'Bs.'],
            ['country_name' => 'Bosnia and Herzegovina', 'country_code' => 'BA', 'currency' => 'BAM', 'currency_symbol' => 'KM'],
            ['country_name' => 'Brazil', 'country_code' => 'BR', 'currency' => 'BRL', 'currency_symbol' => 'R$'],
            ['country_name' => 'Bulgaria', 'country_code' => 'BG', 'currency' => 'BGN', 'currency_symbol' => 'лв'],
            ['country_name' => 'Canada', 'country_code' => 'CA', 'currency' => 'CAD', 'currency_symbol' => '$'],
            ['country_name' => 'China', 'country_code' => 'CN', 'currency' => 'CNY', 'currency_symbol' => '¥'],
            ['country_name' => 'Colombia', 'country_code' => 'CO', 'currency' => 'COP', 'currency_symbol' => '$'],
            ['country_name' => 'Croatia', 'country_code' => 'HR', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'Cuba', 'country_code' => 'CU', 'currency' => 'CUP', 'currency_symbol' => '$'],
            ['country_name' => 'Czech Republic', 'country_code' => 'CZ', 'currency' => 'CZK', 'currency_symbol' => 'Kč'],
            ['country_name' => 'Denmark', 'country_code' => 'DK', 'currency' => 'DKK', 'currency_symbol' => 'kr'],
            ['country_name' => 'Egypt', 'country_code' => 'EG', 'currency' => 'EGP', 'currency_symbol' => '£'],
            ['country_name' => 'Estonia', 'country_code' => 'EE', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'Finland', 'country_code' => 'FI', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'France', 'country_code' => 'FR', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'Germany', 'country_code' => 'DE', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'Ghana', 'country_code' => 'GH', 'currency' => 'GHS', 'currency_symbol' => '₵'],
            ['country_name' => 'Greece', 'country_code' => 'GR', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'India', 'country_code' => 'IN', 'currency' => 'INR', 'currency_symbol' => '₹'],
            ['country_name' => 'Indonesia', 'country_code' => 'ID', 'currency' => 'IDR', 'currency_symbol' => 'Rp'],
            ['country_name' => 'Iran', 'country_code' => 'IR', 'currency' => 'IRR', 'currency_symbol' => '﷼'],
            ['country_name' => 'Iraq', 'country_code' => 'IQ', 'currency' => 'IQD', 'currency_symbol' => 'ع.د'],
            ['country_name' => 'Ireland', 'country_code' => 'IE', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'Israel', 'country_code' => 'IL', 'currency' => 'ILS', 'currency_symbol' => '₪'],
            ['country_name' => 'Italy', 'country_code' => 'IT', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'Japan', 'country_code' => 'JP', 'currency' => 'JPY', 'currency_symbol' => '¥'],
            ['country_name' => 'Jordan', 'country_code' => 'JO', 'currency' => 'JOD', 'currency_symbol' => 'د.ا'],
            ['country_name' => 'Kenya', 'country_code' => 'KE', 'currency' => 'KES', 'currency_symbol' => 'Sh'],
            ['country_name' => 'Kuwait', 'country_code' => 'KW', 'currency' => 'KWD', 'currency_symbol' => 'د.ك'],
            ['country_name' => 'Malaysia', 'country_code' => 'MY', 'currency' => 'MYR', 'currency_symbol' => 'RM'],
            ['country_name' => 'Mexico', 'country_code' => 'MX', 'currency' => 'MXN', 'currency_symbol' => '$'],
            ['country_name' => 'Nepal', 'country_code' => 'NP', 'currency' => 'NPR', 'currency_symbol' => '₨'],
            ['country_name' => 'Netherlands', 'country_code' => 'NL', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'New Zealand', 'country_code' => 'NZ', 'currency' => 'NZD', 'currency_symbol' => '$'],
            ['country_name' => 'Nigeria', 'country_code' => 'NG', 'currency' => 'NGN', 'currency_symbol' => '₦'],
            ['country_name' => 'Norway', 'country_code' => 'NO', 'currency' => 'NOK', 'currency_symbol' => 'kr'],
            ['country_name' => 'Oman', 'country_code' => 'OM', 'currency' => 'OMR', 'currency_symbol' => 'ر.ع.'],
            ['country_name' => 'Pakistan', 'country_code' => 'PK', 'currency' => 'PKR', 'currency_symbol' => '₨'],
            ['country_name' => 'Philippines', 'country_code' => 'PH', 'currency' => 'PHP', 'currency_symbol' => '₱'],
            ['country_name' => 'Poland', 'country_code' => 'PL', 'currency' => 'PLN', 'currency_symbol' => 'zł'],
            ['country_name' => 'Portugal', 'country_code' => 'PT', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'Qatar', 'country_code' => 'QA', 'currency' => 'QAR', 'currency_symbol' => 'ر.ق'],
            ['country_name' => 'Russia', 'country_code' => 'RU', 'currency' => 'RUB', 'currency_symbol' => '₽'],
            ['country_name' => 'Saudi Arabia', 'country_code' => 'SA', 'currency' => 'SAR', 'currency_symbol' => 'ر.س'],
            ['country_name' => 'Singapore', 'country_code' => 'SG', 'currency' => 'SGD', 'currency_symbol' => '$'],
            ['country_name' => 'South Africa', 'country_code' => 'ZA', 'currency' => 'ZAR', 'currency_symbol' => 'R'],
            ['country_name' => 'South Korea', 'country_code' => 'KR', 'currency' => 'KRW', 'currency_symbol' => '₩'],
            ['country_name' => 'Spain', 'country_code' => 'ES', 'currency' => 'EUR', 'currency_symbol' => '€'],
            ['country_name' => 'Sri Lanka', 'country_code' => 'LK', 'currency' => 'LKR', 'currency_symbol' => 'Rs'],
            ['country_name' => 'Sweden', 'country_code' => 'SE', 'currency' => 'SEK', 'currency_symbol' => 'kr'],
            ['country_name' => 'Switzerland', 'country_code' => 'CH', 'currency' => 'CHF', 'currency_symbol' => 'Fr'],
            ['country_name' => 'Thailand', 'country_code' => 'TH', 'currency' => 'THB', 'currency_symbol' => '฿'],
            ['country_name' => 'Turkey', 'country_code' => 'TR', 'currency' => 'TRY', 'currency_symbol' => '₺'],
            ['country_name' => 'Ukraine', 'country_code' => 'UA', 'currency' => 'UAH', 'currency_symbol' => '₴'],
            ['country_name' => 'United Arab Emirates', 'country_code' => 'AE', 'currency' => 'AED', 'currency_symbol' => 'د.إ'],
            ['country_name' => 'United Kingdom', 'country_code' => 'GB', 'currency' => 'GBP', 'currency_symbol' => '£'],
            ['country_name' => 'United States', 'country_code' => 'US', 'currency' => 'USD', 'currency_symbol' => '$'],
            ['country_name' => 'Vietnam', 'country_code' => 'VN', 'currency' => 'VND', 'currency_symbol' => '₫'],
            ['country_name' => 'Zimbabwe', 'country_code' => 'ZW', 'currency' => 'ZWL', 'currency_symbol' => '$'],
        ];

        foreach ($countries as &$country) {
            $country['is_active'] = true;
            $country['created_at'] = Carbon::now();
            $country['updated_at'] = Carbon::now();
        }

        DB::table('countries')->insert($countries);
    }
}
