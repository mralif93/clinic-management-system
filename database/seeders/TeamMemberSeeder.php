<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamMembers = [
            [
                'name' => 'Sarah',
                'title' => 'Senior Clinical Psychologist',
                'photo' => null,
                'bio' => 'Experienced in trauma-informed therapy and evidence-based interventions. Specializes in anxiety, depression, and relationship counseling.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Qistina',
                'title' => 'Clinical Psychologist',
                'photo' => null,
                'bio' => 'Dedicated to providing compassionate mental health care. Focuses on cognitive-behavioral therapy and mindfulness-based approaches.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Andrea',
                'title' => 'Clinical Psychologist',
                'photo' => null,
                'bio' => 'Expert in child and adolescent psychology. Passionate about supporting young people through life transitions and challenges.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Adrianna',
                'title' => 'Clinical Psychologist',
                'photo' => null,
                'bio' => 'Specializes in family therapy and couples counseling. Committed to helping individuals build stronger, healthier relationships.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Farhah',
                'title' => 'Clinical Psychologist',
                'photo' => null,
                'bio' => 'Focuses on stress management and work-life balance. Helps clients develop effective coping strategies for daily challenges.',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Marjan',
                'title' => 'Clinical Psychologist',
                'photo' => null,
                'bio' => 'Expert in mood disorders and emotional regulation. Uses integrative approaches to support clients\' mental wellness journey.',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Aina',
                'title' => 'Clinical Psychologist/Counsellor',
                'photo' => null,
                'bio' => 'Combines clinical psychology expertise with counseling skills. Specializes in grief counseling and life transition support.',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Alya',
                'title' => 'Clinical Psychologist',
                'photo' => null,
                'bio' => 'Dedicated to mental health advocacy and stigma reduction. Provides culturally sensitive therapy for diverse populations.',
                'order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Kelly',
                'title' => 'Clinical Psychologist',
                'photo' => null,
                'bio' => 'Specializes in assessment and diagnosis. Experienced in working with adults facing various mental health challenges.',
                'order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Adeera',
                'title' => 'Counselor',
                'photo' => null,
                'bio' => 'Provides supportive counseling for individuals navigating personal and professional challenges. Warm and empathetic approach.',
                'order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Izzam',
                'title' => 'Counselor',
                'photo' => null,
                'bio' => 'Experienced counselor specializing in men\'s mental health and career counseling. Helps clients achieve personal and professional goals.',
                'order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'Hannan',
                'title' => 'Counselor',
                'photo' => null,
                'bio' => 'Compassionate counselor focused on supporting individuals through difficult times. Specializes in self-esteem and personal growth.',
                'order' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($teamMembers as $member) {
            TeamMember::updateOrCreate(
                ['name' => $member['name']],
                $member
            );
        }

        $this->command->info('Team members seeded successfully.');
    }
}
