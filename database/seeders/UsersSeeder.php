<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User as user;
use Illuminate\Support\Str;
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        user::factory(5)->create();
    }
}
