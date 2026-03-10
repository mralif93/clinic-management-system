<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('bio');
            $table->string('license_number')->nullable()->after('profile_photo');
            $table->date('license_expiry')->nullable()->after('license_number');
            $table->unsignedInteger('years_of_experience')->nullable()->after('license_expiry');
            $table->string('languages_spoken')->nullable()->after('years_of_experience');
            $table->string('clinic_location')->nullable()->after('languages_spoken');
            $table->timestamp('card_issued_at')->nullable()->after('clinic_location');
            $table->date('card_expires_at')->nullable()->after('card_issued_at');
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo',
                'license_number',
                'license_expiry',
                'years_of_experience',
                'languages_spoken',
                'clinic_location',
                'card_issued_at',
                'card_expires_at',
            ]);
        });
    }
};
