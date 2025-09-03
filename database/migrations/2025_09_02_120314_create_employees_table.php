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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_no');
            $table->decimal('salary', 10, 2);
            $table->date('doj'); // Date of Joining
            $table->date('dob'); // Date of Birth
            $table->string('ifsc_code');
            $table->string('account_holder_name');
            $table->string('account_no');
            $table->json('documents')->nullable(); // Store multiple documents
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes(); // 👈 this adds deleted_at column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
