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
                'type'              => 'real',
                'relation'          => 'real',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ],
            [
                'account_name'      => 'Purchases',
                'description'       => 'Purchases account',
                'type'              => 'real',
                'relation'          => 'real',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ]
        ]);

        DB::table('account_details')->insert([
            [
                'account_id'    => '01',
                'name'          => 'Cash account',
                'address'       => 'Cash account',
                'image'         => '/images/real.jpg',
                'status'        => '1'
            ],
            [
                'account_id'    => '02',
                'name'          => 'Sales account',
                'address'       => 'Sales account',
                'image'         => '/images/real.jpg',
                'status'        => '1'
            ],
            [
                'account_id'    => '03',
                'name'          => 'Purchases account',
                'address'       => 'Purchases account',
                'image'         => '/images/real.jpg',
                'status'        => '1'  
            ]
        ]);
    }
}
