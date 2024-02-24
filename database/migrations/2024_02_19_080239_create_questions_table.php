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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('question_setting_id')->constrained('question_settings')->cascadeOnDelete();
            $table->string('question');
            $table->integer('answer');
            $table->integer('user_answer');
            $table->integer('round_no');
            $table->time('start_time')->format('H:i');
            $table->time('end_time')->format('H:i');
            $table->boolean('is_correct');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
