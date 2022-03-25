<?php

namespace Database\Seeders;

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
    // \App\Models\User::factory(10)->create();
    $this->call(TagsTableSeeder::class);
    $this->call(LivesTableSeeder::class);
    $this->call(GiftSeeder::class);
    $this->call(YenToCoinLogsSeeder::class);
    $this->call(TestDataSeeder::class);
  }
}
