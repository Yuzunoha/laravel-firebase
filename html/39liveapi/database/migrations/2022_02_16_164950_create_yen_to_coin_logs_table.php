<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYenToCoinLogsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('yen_to_coin_logs', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id');
      $table->integer('acquired_coin_amount');
      $table->integer('spent_yen_amount');
      $table->dateTime('created_at');
      $table->dateTime('updated_at');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('yen_to_coin_logs');
  }
}
