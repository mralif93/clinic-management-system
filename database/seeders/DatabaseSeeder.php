<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users
        $this->call(UserSeeder::class);

        // Seed announcements (needs users for created_by)
        // $this->call(AnnouncementSeeder::class);

        // Seed services
        // $this->call(ServiceSeeder::class);

        // Seed packages
        // $this->call(PackageSeeder::class);

        // Seed team members
        // $this->call(TeamMemberSeeder::class);

        // Seed settings
        // $this->call(SettingsSeeder::class);

        // Seed doctor schedules
        // $this->call(DoctorScheduleSeeder::class);

        // Seed payroll settings
        // $this->call(PayrollSettingsSeeder::class);

        // Seed appointments
        // $this->call(AppointmentSeeder::class);

        // Seed attendance
        // $this->call(AttendanceSeeder::class);

        // Seed leaves
        // $this->call(LeaveSeeder::class);

        // Seed payrolls
        // $this->call(PayrollSeeder::class);

        // Seed todos
        // $this->call(TodoSeeder::class);
    }
}

