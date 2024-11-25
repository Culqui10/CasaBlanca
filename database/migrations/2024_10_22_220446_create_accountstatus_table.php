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
        Schema::create('accountstatus', function (Blueprint $table) {
            $table->id();
            $table->double('current_balance')->default(0);
            $table->string('status'); // Asigna un valor por defecto
            $table->timestamps();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('pensioner_id');
            $table->unsignedBigInteger('consumption_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            $table->foreign('pensioner_id')->references('id')->on('pensioners');
            $table->foreign('consumption_id')->references('id')->on('consumptions')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountstatus');
    }
};
