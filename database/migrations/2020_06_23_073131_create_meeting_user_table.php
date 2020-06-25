<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingUserTable extends Migration
{
     public function up()
     {
          Schema::create('meeting_user', function (Blueprint $table) {
               $table->increments('id');
               $table->integer('user_id')->unsigned();
               $table->integer('meeting_id')->unsigned();

               $table->foreign('user_id')->references('id')
               ->on('users')->onDelete('cascade');
               $table->foreign('meeting_id')->references('id')
               ->on('meetings')->onDelete('cascade');
          });
     }

     public function down()
     {
          Schema::dropIfExists('meeting_users');
     }
}
