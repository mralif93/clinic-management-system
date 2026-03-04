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
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('record_approved_by')
                ->nullable()
                ->after('room_number')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('record_approved_at')
                ->nullable()
                ->after('record_approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['record_approved_by']);
            $table->dropColumn(['record_approved_by', 'record_approved_at']);
        });
    }
};
