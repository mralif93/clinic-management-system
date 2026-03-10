<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('notes');
            $table->string('nric')->nullable()->after('profile_photo');
            $table->string('blood_type')->nullable()->after('nric');
            $table->string('emergency_contact_name')->nullable()->after('blood_type');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('clinic_location')->nullable()->after('emergency_contact_phone');
            $table->timestamp('card_issued_at')->nullable()->after('clinic_location');
            $table->date('card_expires_at')->nullable()->after('card_issued_at');
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo',
                'nric',
                'blood_type',
                'emergency_contact_name',
                'emergency_contact_phone',
                'clinic_location',
                'card_issued_at',
                'card_expires_at',
            ]);
        });
    }
};
