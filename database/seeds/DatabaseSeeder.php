<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PostTableSeeder::class);
        //$this->call(TagsTableSeeder::class);
        //factory(App\Models\User::class, 3)->make();
    }
}
