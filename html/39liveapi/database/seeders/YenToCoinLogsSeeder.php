<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YenToCoinLogsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    /*
mysql> desc yen_to_coin_logs;
+----------------------+-----------------+------+-----+---------+----------------+
| Field                | Type            | Null | Key | Default | Extra          |
+----------------------+-----------------+------+-----+---------+----------------+
| id                   | bigint unsigned | NO   | PRI | NULL    | auto_increment |
| user_id              | int             | NO   |     | NULL    |                |
| acquired_coin_amount | int             | NO   |     | NULL    |                |
| spent_yen_amount     | int             | NO   |     | NULL    |                |
| created_at           | datetime        | NO   |     | NULL    |                |
| updated_at           | datetime        | NO   |     | NULL    |                |
+----------------------+-----------------+------+-----+---------+----------------+
6 rows in set (0.00 sec)
     */
    $now = date('Y-m-d H:i:s');
    $items[] = [1, 20000];
    $items[] = [1, 30000];
    $items[] = [1, 30000];
    $items[] = [1, 5000];
    $items[] = [1, 10000];
    foreach ($items as $item) {
      $array[] = [
        'user_id'              => $item[0],
        'acquired_coin_amount' => $item[1] / 2,
        'spent_yen_amount'     => $item[1],
        'created_at'           => $now,
        'updated_at'           => $now,
      ];
    }
    DB::table('yen_to_coin_logs')->insert($array);
  }
}
