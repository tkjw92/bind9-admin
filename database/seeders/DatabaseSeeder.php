<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('tb_config')->insert([
            'allowed' => 'any;',
            'recursion' => 'no',
            'f1' => null,
            'f2' => null,
            'f3' => null,
            'f4' => null,
        ]);

        DB::table('tb_account')->insert([
            'username' => 'admin',
            'password' => 'adminissuperpower'
        ]);
    }
}
