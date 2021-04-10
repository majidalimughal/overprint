<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')
            ->insert([
                'name'=>'Super Admin',
                'email'=>'superadmin@overprint.com',
                'password'=>Hash::make('overprintadmin@1122'),
                'role'=>'admin'
            ]);
        DB::table('users')
            ->insert([
                'name'=>'Supplier',
                'email'=>'supplier@overprint.com',
                'password'=>Hash::make('overprintsupplier@1122'),
                'role'=>'supplier'
            ]);
    }
}
