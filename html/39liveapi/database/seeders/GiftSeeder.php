<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
mysql> desc gifts;
+--------------+-----------------+------+-----+---------+----------------+
| Field        | Type            | Null | Key | Default | Extra          |
+--------------+-----------------+------+-----+---------+----------------+
| id           | bigint unsigned | NO   | PRI | NULL    | auto_increment |
| name         | varchar(255)    | NO   |     | NULL    |                |
| coin_amount  | int             | NO   |     | NULL    |                |
| point_amount | int             | NO   |     | NULL    |                |
| created_at   | timestamp       | YES  |     | NULL    |                |
| updated_at   | timestamp       | YES  |     | NULL    |                |
+--------------+-----------------+------+-----+---------+----------------+
        */
        $now = date('Y-m-d H:i:s');
        $items[] = ['キャンディ', 100, 100,];
        $items[] = ['紅茶', 200, 200,];
        $items[] = ['ハンバーガー', 300, 300,];
        $items[] = ['ケーキ', 400, 400,];
        $items[] = ['風船', 500, 500,];
        $items[] = ['ふわふわくまさん', 600, 600,];
        $items[] = ['ハート', 700, 700,];
        $items[] = ['クラッカー', 800, 800,];
        $items[] = ['いいね！', 900, 900,];
        $items[] = ['花火', 1000, 1000,];
        $items[] = ['大花火', 2000, 2000,];
        foreach ($items as $item) {
            $array[] = [
                'name' => $item[0],
                'coin_amount' => $item[1],
                'point_amount' => $item[2],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('gifts')->insert($array);
    }
}
