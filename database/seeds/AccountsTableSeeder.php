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
                'account_name'      => 'Cash', //account id : 1
                'description'       => 'Cash account',
                'type'              => 'real',
                'relation'          => 'real',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'
            ],
            [
                'account_name'      => 'Sales', //account id : 2
                'description'       => 'Sales account',
                'type'              => 'nominal',
                'relation'          => 'nominal',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ],
            [
                'account_name'      => 'Purchases', //account id : 3
                'description'       => 'Purchases account',
                'type'              => 'nominal',
                'relation'          => 'nominal',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ],
            [
                'account_name'      => 'Labour Attendance', //account id : 4
                'description'       => 'Labour attendance account',
                'type'              => 'nominal',
                'relation'          => 'nominal',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ],
            [
                'account_name'      => 'Excavator Reading', //account id : 5
                'description'       => 'Excavator reading account',
                'type'              => 'nominal',
                'relation'          => 'nominal',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ],
            [
                'account_name'      => 'Jackhammer Reading', //account id : 6
                'description'       => 'Jackhammer reading account',
                'type'              => 'nominal',
                'relation'          => 'nominal',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ],
            [
                'account_name'      => 'Employee Salary', //account id : 7
                'description'       => 'Employee salary account',
                'type'              => 'nominal',
                'relation'          => 'nominal',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ],
            [
                'account_name'      => 'Excavator Rent', //account id : 8
                'description'       => 'Excavator rent account',
                'type'              => 'nominal',
                'relation'          => 'nominal',
                'financial_status'  => 'none',
                'opening_balance'   => '0',
                'status'            => '1'  
            ],
            [
                'account_name'      => 'Sale Royalty', //account id : 9
                'description'       => 'Sale royalty account',
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
            ],
            [
                'account_id'    => '07',
                'name'          => 'Employe salary account',
                'address'       => '',
                'image'         => '/images/real.jpg',
                'status'        => '1'  
            ],
            [
                'account_id'    => '08',
                'name'          => 'Excavator rent account',
                'address'       => '',
                'image'         => '/images/real.jpg',
                'status'        => '1'  
            ],
            [
                'account_id'    => '09',
                'name'          => 'Sale Royalty account',
                'address'       => '',
                'image'         => '/images/real.jpg',
                'status'        => '1'  
            ]
        ]);
    }
}
