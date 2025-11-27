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
        Schema::table('todos', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('assigned_to');
            $table->enum('recurrence_type', ['daily', 'weekly', 'monthly'])->nullable()->after('is_recurring');
            $table->date('last_generated_date')->nullable()->after('recurrence_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn(['is_recurring', 'recurrence_type', 'last_generated_date']);
        });
    }
};
