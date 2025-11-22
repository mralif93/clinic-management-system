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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('doctor_id'); // Auto-generated identifier (DOC-001)
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('specialization')->nullable();
            $table->string('qualification')->nullable();
            $table->text('bio')->nullable();
            $table->string('type')->default('general'); // psychology, homeopathy, general
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Add unique constraints separately for PostgreSQL compatibility
        Schema::table('doctors', function (Blueprint $table) {
            $table->unique('doctor_id', 'doctors_doctor_id_unique');
            $table->unique('email', 'doctors_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};

