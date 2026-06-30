<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    protected function createAdminUser(array $attributes = []): \App\Models\User
    {
        return \App\Models\User::factory()->admin()->create($attributes);
    }
}
