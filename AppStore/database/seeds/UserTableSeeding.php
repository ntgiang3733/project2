<?php

use Illuminate\Database\Seeder;

class UserTableSeeding extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Nguyen Truong Giang',
            'email' => 'ntgiang3733@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 1
        ]);
    }
}
