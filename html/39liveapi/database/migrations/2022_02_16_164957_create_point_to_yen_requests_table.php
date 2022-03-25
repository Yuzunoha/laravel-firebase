<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointToYenRequestsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('point_to_yen_requests', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id');
      $table->integer('point_amount');
      $table->integer('yen_amount');
      $table->string('point_to_yen_status');
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
    Schema::dropIfExists('point_to_yen_requests');
  }
}
