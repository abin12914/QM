<?php

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            [
                'account_name'      => 'Cash',
                'description'       => 'Cash account',
                'type'              => 'real',
                'relation'          => 'real',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'
            ],
            [
                'account_name'      => 'Sales',
                'description'       => 'Sales account',
                'type'              => 'nominal',
                'relation'          => 'nominal',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ],
            [
                'account_name'      => 'Purchases',
                'description'       => 'Purchases account',
                'type'              => 'nominal',
                'relation'          => 'nominal',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ],
            [
                'account_name'      => 'Labour Attendance',
                'description'       => 'Labour attendance account',
                'type'              => 'nominal',
                'relation'          => 'nominal',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ],
            [
                'account_name'      => 'Excavator Reading',
                'description'       => 'Excavator reading account',
                'type'              => 'nominal',
                'relation'          => 'nominal',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ],
            [
                'account_name'      => 'Jackhammer Reading',
                'description'       => 'Jackhammer reading account',
                'type'              => 'nominal',
                'relation'          => 'nominal',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ]
        ]);

        DB::table('account_details')->insert([
            [
                'account_id'    => '01',
                'name'          => 'Cash account',
                'address'       => '',
                'image'         => '/images/real.jpg',
                'status'        => '1'
            ],
            [
                'account_id'    => '02',
                'name'          => 'Sales account',
                'address'       => '',
                'image'         => '/images/real.jpg',
                'status'        => '1'
            ],
            [
                'account_id'    => '03',
                'name'          => 'Purchases account',
                'address'       => '',
                'image'         => '/images/real.jpg',
                'status'        => '1'  
            ],
            [
                'account_id'    => '04',
                'name'          => 'Labour attendance account',
                'address'       => '',
                'image'         => '/images/real.jpg',
                'status'        => '1'  
            ],
            [
                'account_id'    => '05',
                'name'          => 'Excavator reading account',
                'address'       => '',
                'image'         => '/images/real.jpg',
                'status'        => '1'  
            ],
            [
                'account_id'    => '06',
                'name'          => 'Jackhammer reading account',
                'address'       => '',
                'image'         => '/images/real.jpg',
                'status'        => '1'  
            ]
        ]);
    }
}
