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
        Schema::create('question_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('row');
            $table->tinyInteger('digit');
            $table->time('speed')->format('H:i');
            $table->integer('round')->default(1);
            $table->boolean('completed')->default(false);

            $table->enum('level',['l1', 'l2']);
            $table->enum('show_type',['speech', 'words']);

            $table->boolean('exam')->default(false);
            $table->time('exam_time')->format('H:i');
            $table->string('exam_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_settings');
    }
};
