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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('code');
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->float('freight')->default(0);
            $table->float('discount')->default(0);
            $table->float('entry')->default(0);
            $table->float('order_value');
            $table->float('products_value');
            $table->integer('products_amount');
            $table->foreignId('payment_id')->constrained();
            $table->foreignId('delivery_id')->constrained();
            $table->float('delivery_price')->default(0);
            $table->string('port_name')->nullable();
            $table->string('deliverer')->nullable();
            $table->foreignId('status_id')->constrained();
            $table->boolean('valid')->default(true);
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
