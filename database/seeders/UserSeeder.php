<?php

namespace Database\Seeders;

use App;
use Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new App\Models\User();
        $user->name = 'trial';
        $user->email = 'trial@mail.com';
        $user->password = Hash::make('trial123');
        $user->is_admin = true;
        $user->save();
    }
}
