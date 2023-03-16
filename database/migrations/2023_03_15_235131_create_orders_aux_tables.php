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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('installmentable')->default(false);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
        Schema::create('delivery', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_paid');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('delivery');
    }
};
