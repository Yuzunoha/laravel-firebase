<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGiftEntriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_gift_entries', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id');
      $table->integer('gift_id');
      $table->string('gift_status'); // 状態。enum定義は config/const.php にあり
      $table->dateTime('expiration_datetime'); // 有効期限。無期限の場合の値は「9999-12-31 23:59:59」
      $table->integer('to_user_id')->nullable(); // プレゼント先のuid
      $table->integer('point_amount_base')->nullable(); // 倍率適用前のギフトポイント
      $table->integer('point_amount_calculated')->nullable(); // 倍率適用後のギフトポイント
      $table->dateTime('created_at');
      $table->dateTime('updated_at');
      $table->index([
        'user_id',
        'gift_id',
        'gift_status',
        'expiration_datetime',
      ], 'index_for_basic_filter');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('user_gift_entries');
  }
}
