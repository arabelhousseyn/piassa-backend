<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->double('amount');
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
        Schema::dropIfExists('company_commissions');
    }
}
