<?php

use Illuminate\Database\Seeder;

class ApiChannelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('api_channels')->truncate();
        DB::table('api_channels')->insert([
            [
                'id' => 1,
                'name' => 'Sendgrid'
            ],
            [
                'id' => 2,
                'name' => 'Mailgun'
            ]
        ]);
    }
}
