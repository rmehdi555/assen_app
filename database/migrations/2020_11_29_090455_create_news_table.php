<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('news_categories_id')->unsigned();
            $table->foreign('news_categories_id')->references('id')->on('news_categories')->onDelete('cascade');
            $table->text('slug');
            $table->text('description')->nullable();
            $table->text('body');
            $table->string('type')->default('normal');
            $table->text('images');
            $table->text('tags');
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->integer('seo_follow')->default(1);
            $table->integer('seo_index')->nullable(1);
            $table->integer('priority')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
