<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_id')->nullable();
            $table->string('price')->nullable();
            $table->integer('order_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('user_type')->nullable();
            $table->string('mobile')->nullable();
            $table->text('callbackURL')->nullable();
            $table->text('authority')->nullable();
            $table->text('refId')->nullable();
            $table->text('extraDetail')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
