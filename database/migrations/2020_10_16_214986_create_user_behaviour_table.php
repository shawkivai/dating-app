<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBehaviourTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_behaviours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->unsignedBigInteger('like_dislike_user_id');
            $table->foreign('like_dislike_user_id')->references('id')->on('users');
            $table->boolean('is_liked')->default(false);
            $table->boolean('is_disliked')->default(false);
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
        Schema::dropIfExists('user_behaviours');
    }
}
