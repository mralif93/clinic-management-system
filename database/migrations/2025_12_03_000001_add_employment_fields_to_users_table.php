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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('employment_type', ['full_time', 'part_time', 'locum'])->default('full_time')->after('role');
            $table->decimal('basic_salary', 10, 2)->nullable()->after('employment_type');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('basic_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employment_type', 'basic_salary', 'hourly_rate']);
        });
    }
};

