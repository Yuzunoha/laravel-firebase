<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsTableSeeder extends Seeder
{
    protected $seederUtil;

    public function __construct(SeederUtil $seederUtil)
    {
        $this->seederUtil = $seederUtil;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $tagNames = $this->seederUtil->getTagNames();
        foreach ($tagNames as $tagName) {
            $array[] = [
                'name' => $tagName,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('tags')->insert($array);
    }
}
