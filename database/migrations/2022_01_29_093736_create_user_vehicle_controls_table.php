<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVehicleControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vehicle_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_vehicle_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->date('technical_control');
            $table->date('assurance');
            $table->date('emptying');
            $table->unique(['user_vehicle_id']);
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
        Schema::dropIfExists('user_vehicle_controls');
    }
}
