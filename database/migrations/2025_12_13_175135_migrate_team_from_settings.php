<?php

use App\Models\TeamMember;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run if team_members table exists
        if (!Schema::hasTable('team_members')) {
            return;
        }

        // Check if team members already exist in database
        if (TeamMember::count() > 0) {
            return;
        }

        // Get team_members from Settings
        $teamMembersJson = Setting::get('team_members');
        
        if (!$teamMembersJson) {
            return;
        }

        // Decode JSON
        $teamMembers = json_decode($teamMembersJson, true);
        
        if (!is_array($teamMembers) || empty($teamMembers)) {
            return;
        }

        // Migrate each team member
        foreach ($teamMembers as $index => $memberData) {
            try {
                TeamMember::create([
                    'name' => $memberData['name'] ?? 'Team Member ' . ($index + 1),
                    'title' => $memberData['title'] ?? null,
                    'bio' => $memberData['bio'] ?? null,
                    'photo' => $memberData['photo'] ?? null,
                    'order' => $index + 1, // Preserve order from array
                    'is_active' => true,
                ]);
            } catch (\Exception $e) {
                // Log error but continue with other members
                \Log::warning("Failed to migrate team member: " . $e->getMessage());
                continue;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally remove migrated team members
        // TeamMember::truncate(); // Uncomment if you want to remove all team members on rollback
    }
};
