<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('theme_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('title');
            $table->text('content');
            $table->integer('views')->default('0');
            $table->integer('commentsNumber')->default('0');
            $table->integer('likes')->default('0');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->unsigned();
            $table->foreign('theme_id')->references('id')->on('forum_themes')->onDelete('cascade')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forum_posts');
    }
}
