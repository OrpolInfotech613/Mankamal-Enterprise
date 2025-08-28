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
        Schema::create('order_steps', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->foreignId('o_id')->constrained('orders')->onDelete('cascade')->comment('Order ID');
            $table->foreignId('d_id')->constrained('dealers')->onDelete('cascade')->comment('Dealer ID');
            $table->integer('step_order')->default(1)->comment('Step sequence number');
            $table->enum('status', ['pending', 'completed', 'progress'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index('o_id');
            $table->index('d_id');
            $table->index('status');
            $table->index(['o_id', 'step_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_steps');
    }
};
