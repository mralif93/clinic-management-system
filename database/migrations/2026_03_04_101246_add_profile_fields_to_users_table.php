<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('position')->nullable()->after('phone');   // e.g. "Clinic Manager"
            $table->date('date_of_birth')->nullable()->after('position');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->text('address')->nullable()->after('gender');
            $table->text('bio')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'position', 'date_of_birth', 'gender', 'address', 'bio']);
        });
    }
};
