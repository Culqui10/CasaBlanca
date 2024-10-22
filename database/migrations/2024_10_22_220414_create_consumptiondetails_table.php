<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consumptiondetails', function (Blueprint $table) {
            $table->id();
            $table->string('aditional',100)->nullable();
            $table->double('aditional_cost',10,8)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('consumption_id');
            $table->foreign('menu_id')->references('id')->on('menus');
            $table->foreign('consumption_id')->references('id')->on('consumptions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumptiondetails');
    }
};
