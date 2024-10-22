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
        Schema::create('pensioners', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('lastname',100);
            $table->string('phone',100);
            $table->string('location',100);
            $table->string('name_representative',100)->nullable();
            $table->string('phone_representative',100)->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pensioners');
    }
};
