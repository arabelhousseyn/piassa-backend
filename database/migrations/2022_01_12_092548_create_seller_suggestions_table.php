<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_request_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('mark');
            $table->double('price');
            $table->date('available_at');
            $table->timestamp('taken_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
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
        Schema::dropIfExists('seller_suggestions');
    }
}
