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
        Schema::create('stepups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unsigned();
            $table->string('img')->nullable();
            $table->string('first_step')->nullable();
            $table->time('second_step')->format('H:i')->nullable();
            $table->json('third_step')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stepups');
    }
};
