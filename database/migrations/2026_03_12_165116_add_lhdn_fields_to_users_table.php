<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('marital_status')->default('single')->after('gender'); // single, married, married_spouse_working, married_spouse_not_working
            $table->integer('number_of_children')->default(0)->after('marital_status');
            $table->string('tax_number')->nullable()->after('number_of_children');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['marital_status', 'number_of_children', 'tax_number']);
        });
    }
};
