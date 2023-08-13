<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    public $currencies = [
        ['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$'],
        ['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€'],
        ['name' => 'Japanese Yen', 'code' => 'JPY', 'symbol' => '¥'],
        ['name' => 'British Pound', 'code' => 'GBP', 'symbol' => '£'],
        ['name' => 'Australian Dollar', 'code' => 'AUD', 'symbol' => '$'],
        ['name' => 'Canadian Dollar', 'code' => 'CAD', 'symbol' => '$'],
        ['name' => 'Swiss Franc', 'code' => 'CHF', 'symbol' => 'CHF'],
        ['name' => 'Chinese Yuan Renminbi', 'code' => 'CNY', 'symbol' => '¥'],
        ['name' => 'Hong Kong Dollar', 'code' => 'HKD', 'symbol' => '$'],
        ['name' => 'New Zealand Dollar', 'code' => 'NZD', 'symbol' => '$'],
        ['name' => 'Swedish Krona', 'code' => 'SEK', 'symbol' => 'kr'],
        ['name' => 'South Korean Won', 'code' => 'KRW', 'symbol' => '₩'],
        ['name' => 'Singapore Dollar', 'code' => 'SGD', 'symbol' => '$'],
        ['name' => 'Norwegian Krone', 'code' => 'NOK', 'symbol' => 'kr'],
        ['name' => 'Mexican Peso', 'code' => 'MXN', 'symbol' => '$'],
        ['name' => 'Indian Rupee', 'code' => 'INR', 'symbol' => '₹'],
        ['name' => 'Russian Ruble', 'code' => 'RUB', 'symbol' => '₽'],
        ['name' => 'South African Rand', 'code' => 'ZAR', 'symbol' => 'R'],
        ['name' => 'Turkish Lira', 'code' => 'TRY', 'symbol' => '₺'],
        ['name' => 'Brazilian Real', 'code' => 'BRL', 'symbol' => 'R$'],
        ['name' => 'Thai Baht', 'code' => 'THB', 'symbol' => '฿'],
        ['name' => 'Danish Krone', 'code' => 'DKK', 'symbol' => 'kr'],
        ['name' => 'Polish Zloty', 'code' => 'PLN', 'symbol' => 'zł'],
        ['name' => 'Israeli Shekel', 'code' => 'ILS', 'symbol' => '₪'],
        ['name' => 'Czech Koruna', 'code' => 'CZK', 'symbol' => 'Kč'],
        ['name' => 'Hungarian Forint', 'code' => 'HUF', 'symbol' => 'Ft'],
        ['name' => 'Chilean Peso', 'code' => 'CLP', 'symbol' => '$'],
        ['name' => 'Philippine Peso', 'code' => 'PHP', 'symbol' => '₱'],
        ['name' => 'Indonesian Rupiah', 'code' => 'IDR', 'symbol' => 'Rp'],
        ['name' => 'Malaysian Ringgit', 'code' => 'MYR', 'symbol' => 'RM'],
        ['name' => 'Emirati Dirham', 'code' => 'AED', 'symbol' => 'د.إ'],
        ['name' => 'Saudi Riyal', 'code' => 'SAR', 'symbol' => '﷼'],
        ['name' => 'Qatari Riyal', 'code' => 'QAR', 'symbol' => '﷼'],
        ['name' => 'Kuwaiti Dinar', 'code' => 'KWD', 'symbol' => 'د.ك'],
        ['name' => 'Bahraini Dinar', 'code' => 'BHD', 'symbol' => '.د.ب'],
        ['name' => 'Omani Rial', 'code' => 'OMR', 'symbol' => '﷼'],
        ['name' => 'Jordanian Dinar', 'code' => 'JOD', 'symbol' => 'د.ا'],
        ['name' => 'Lebanese Pound', 'code' => 'LBP', 'symbol' => 'ل.ل'],
        ['name' => 'Egyptian Pound', 'code' => 'EGP', 'symbol' => 'E£'],
        ['name' => 'Bangladeshi Taka', 'code' => 'BDT', 'symbol' => '৳'],
    ];

    public function run(): void
    {

        foreach ($this->currencies as $currency) {

            DB::table('currencies')->insert([
                'name' => $currency['name'],
                'code' => $currency['code'],
                'symbol' => $currency['symbol'],
            ]);
        }

    }
}
