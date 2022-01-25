<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipperCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipper_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('start_coordination');
            $table->foreignId('end_coordination')->nullable();
            $table->double('amount')->nullable();
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
        Schema::dropIfExists('shipper_commissions');
    }
}
