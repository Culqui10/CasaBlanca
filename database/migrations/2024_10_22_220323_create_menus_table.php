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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->double('price',10,8);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('typefood_id');
            $table->foreign('typefood_id')->references('id')->on('typefoods');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
