<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApplicationBootsTest extends TestCase
{
    public function test_application_bootstraps_in_testing_environment(): void
    {
        $this->assertSame('testing', app()->environment());
        $this->assertNotEmpty(config('app.name'));
    }
}

