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
        Schema::create('sale_records', function (Blueprint $table) {
            $table->id();
            $table->integer('book_id');
            $table->integer('customer_id');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('amount');
            $table->integer('paid');
            $table->integer('due');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_records');
    }
};
