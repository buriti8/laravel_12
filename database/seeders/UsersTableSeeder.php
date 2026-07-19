<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
            'name' => 'Administrador',
            'username' => 'admin',
            'password' => bcrypt('AdminAIG$%'),
            'email' => 'buriti8@gmail.com',
            'is_admin' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        $user = User::where('username', 'admin')->first();
        $user->assignRole('Administrador');
    }
}
