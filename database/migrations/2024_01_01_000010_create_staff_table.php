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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('staff_id')->unique()->after('user_id'); // Auto-generated identifier (STF-001)
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('position')->nullable(); // e.g., Receptionist, Nurse, Administrator
            $table->string('department')->nullable();
            $table->date('hire_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};

