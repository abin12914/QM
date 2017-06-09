<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
                'name'      => 'abin_12914',
                'user_name' => 'superadmin@quarrymanager',
                'email'     => 'abin111abin@gmail.com',
                'phone'     => '+918714439950',
                'password'  => Hash::make('123456'),
                'image'     => '/images/user/default_super_admin.png',
                'role'      => 'superadmin',
                'status'    => '1'
        ]);
    }
}
