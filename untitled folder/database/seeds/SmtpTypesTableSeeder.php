<?php

use Illuminate\Database\Seeder;

class SmtpTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('smtp_types')->truncate();
        DB::table('smtp_types')->insert([
            [
                'id' => 1,
                'name' => 'Sendgrid'
            ],
            [
                'id' => 2,
                'name' => 'Mailgun'
            ],
            [
                'id' => 3,
                'name' => 'Others'
            ]
        ]);
    }
}
