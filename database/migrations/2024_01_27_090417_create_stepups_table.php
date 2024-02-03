<?php

use App\Models\FirstStep;
use App\Models\SecondStep;
use App\Models\User;
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
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(FirstStep::class)->nullable();
            // $table->foreignIdFor(SecondStep::class)->nullable();
            $table->string('img')->nullable();
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
