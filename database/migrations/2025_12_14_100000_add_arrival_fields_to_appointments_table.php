<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->timestamp('arrived_at')->nullable()->after('confirmed_at');
            $table->foreignId('accepted_by')->nullable()->after('arrived_at')->constrained('users')->nullOnDelete();
            $table->timestamp('accepted_at')->nullable()->after('accepted_by');
            $table->string('room_number', 20)->nullable()->after('accepted_at');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['accepted_by']);
            $table->dropColumn(['arrived_at', 'accepted_by', 'accepted_at', 'room_number']);
        });
    }
};
