<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOrderInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_order_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('path');
            $table->unique(['user_order_id']);
            $table->softDeletes();
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
        Schema::dropIfExists('user_order_invoices');
    }
}
