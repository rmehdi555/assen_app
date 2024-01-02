<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('tag_title');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('product_categories_id')->unsigned();
            $table->foreign('product_categories_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->text('slug');
            $table->text('description')->nullable();
            $table->text('body');
            $table->string('price',50);
            $table->string('price_usd',50)->default(0);
            $table->string('price_euro',50)->default(0);
            $table->string('price_old',50)->nullable();
            $table->string('size',50);
            $table->string('standard',50);
            $table->string('unit',50);
            $table->double('discount')->default(0);
            $table->string('type')->default('normal');
            $table->enum('place_of_delivery', ['store', 'factory'])->default('store');
            $table->text('images');
            $table->text('tags');
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->integer('seo_follow')->default(1);
            $table->integer('seo_index')->nullable(1);
            $table->text('schema')->nullable();
            $table->bigInteger('factory_id')->default(1);
            $table->bigInteger('standard_id')->default(1);
            $table->bigInteger('size_id')->default(1);
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
        Schema::dropIfExists('products');
    }
}
