<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('ingredients');
            $table->text('procedures');
            $table->text('tag');
            $table->enum('category',['desert','soup','breakfast', 'lunch', 'dinner']);
            $table->Integer('price')->nullable();
            $table->string('video_url')->nullable();
            $table->string('img_url');
            $table->string('status');
            $table->unsignedBigInteger('user_id');

            //foriegn key user
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipes');
    }
}
