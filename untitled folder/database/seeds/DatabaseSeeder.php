<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(ThemeTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(TimeZonesTableSeeder::class);
        $this->call(SmtpTypesTableSeeder::class);
        $this->call(ApiChannelTableSeeder::class);
    }
}
