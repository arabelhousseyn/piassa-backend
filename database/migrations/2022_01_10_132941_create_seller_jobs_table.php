<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('job');
            $table->enum('type',['P','C','B']);
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
        Schema::dropIfExists('seller_jobs');
    }
}
