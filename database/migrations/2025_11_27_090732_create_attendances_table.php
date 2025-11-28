<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->dateTime('clock_in_time');
            $table->dateTime('clock_out_time')->nullable();
            $table->string('clock_in_location')->nullable(); // IP or lat,lng
            $table->string('clock_out_location')->nullable();
            $table->enum('status', ['present', 'late', 'half_day', 'absent', 'on_leave'])->default('present');
            $table->decimal('total_hours', 5, 2)->nullable();
            $table->integer('break_duration')->default(0); // in minutes
            $table->text('notes')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->unique(['user_id', 'date']);
            $table->index('status');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
