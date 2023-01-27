<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Recipe;
use App\Models\User;
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
        User::factory(5)->create();
        Recipe::factory()->create();
        Bookmark::factory()->create();
    }
}
