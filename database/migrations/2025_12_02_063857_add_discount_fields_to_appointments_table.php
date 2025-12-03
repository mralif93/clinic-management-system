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
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable()->after('fee');
            $table->decimal('discount_value', 10, 2)->nullable()->after('discount_type');
            $table->string('payment_status')->default('unpaid')->after('discount_value'); // unpaid, paid, partial
            $table->string('payment_method')->nullable()->after('payment_status'); // cash, card, online, insurance
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_value', 'payment_status', 'payment_method']);
        });
    }
};
