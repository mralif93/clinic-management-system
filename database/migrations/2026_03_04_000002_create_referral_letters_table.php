<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('referral_letters', function (Blueprint $table) {
            $table->id();
            $table->string('referral_number')->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();

            // Referred-to details
            $table->string('referred_to_name');
            $table->string('referred_to_facility');
            $table->string('referred_to_specialty');

            // Clinical content
            $table->text('reason');
            $table->text('clinical_notes')->nullable();

            // Meta
            $table->enum('urgency', ['routine', 'urgent', 'emergency'])->default('routine');
            $table->date('valid_until')->nullable();
            $table->enum('status', ['draft', 'issued'])->default('draft');
            $table->timestamp('issued_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_letters');
    }
};
