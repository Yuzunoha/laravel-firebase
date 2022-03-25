<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LivesTableSeeder extends Seeder
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
        $objs = $this->seederUtil->getTagIdNameObjects();
        $liveNum = 100;
        $tagNumPerLive = 5;
        for ($i = 0; $i < $liveNum; $i++) {
            shuffle($objs);
            $liveName = '';
            $tid_csv = '';
            for ($j = 0; $j < $tagNumPerLive; $j++) {
                $tid_csv .= $objs[$j]['id'] . ',';
                $liveName .= $objs[$j]['tagName'] . ',';
            }
            $liveName .= 'のLive!';

            $array[] = [
                'user_id' => 1,
                'name' => $liveName,
                'tid_csv' => $tid_csv,
                'created_at' => $now,
                'updated_at' => $now,
                'finished_at' => ($i < $liveNum / 2) ? $now : null,
            ];
        }
        DB::table('lives')->insert($array);
    }
}
