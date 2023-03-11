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
        Schema::table('specs', function (Blueprint $table) {
            $table->string('name')->unique()->change();
        });
        Schema::table('types', function (Blueprint $table) {
            $table->string('name')->unique()->change();
        });
        Schema::table('models', function (Blueprint $table) {
            $table->string('name')->unique()->change();
        });
        Schema::table('colors', function (Blueprint $table) {
            $table->string('name')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
