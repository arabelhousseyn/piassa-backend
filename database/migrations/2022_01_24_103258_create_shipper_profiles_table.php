<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipperProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipper_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipper_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('province_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('device_token')->nullable();
            $table->unique(['shipper_id']);
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
        Schema::dropIfExists('shipper_profiles');
    }
}
