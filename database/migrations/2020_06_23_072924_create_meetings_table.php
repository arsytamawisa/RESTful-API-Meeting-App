<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingsTable extends Migration
{
     public function up()
     {
          Schema::create('meetings', function (Blueprint $table) {
               $table->increments('id');
               $table->string('title', 50);
               $table->text('desc');
               $table->timestamps();
          });
     }

     public function down()
     {
          Schema::dropIfExists('meetings');
     }
}
