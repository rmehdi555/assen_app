<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factories', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('tag_title');
            $table->text('slug')->nullable();
            $table->text('body')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('product_categories_id')->unsigned();
            $table->foreign('product_categories_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->text('images');
            $table->integer('priority')->default(0);
            $table->integer('status')->default(0);
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->integer('seo_follow')->default(1);
            $table->integer('seo_index')->nullable(1);
            $table->text('seo_canonical')->nullable();
            $table->text('schema')->nullable();
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
        Schema::dropIfExists('web_pages');
    }
}
