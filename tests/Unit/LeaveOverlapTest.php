<?php

namespace Tests\Unit;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveOverlapTest extends TestCase
{
    use RefreshDatabase;

    public function test_detects_overlapping_leaves(): void
    {
        $user = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@example.com',
            'password' => 'secret',
            'role' => 'staff',
        ]);

        Leave::create([
            'user_id' => $user->id,
            'leave_type' => Leave::TYPE_ANNUAL,
            'start_date' => '2025-01-10',
            'end_date' => '2025-01-12',
            'total_days' => 3,
            'reason' => 'Holiday',
            'status' => Leave::STATUS_APPROVED,
        ]);

        $this->assertTrue(Leave::hasOverlap($user->id, '2025-01-11', '2025-01-13'));
        $this->assertFalse(Leave::hasOverlap($user->id, '2025-01-15', '2025-01-16'));
    }
}

