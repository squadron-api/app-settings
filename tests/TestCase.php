<?php

namespace Squadron\AppSettings\Tests;

use Squadron\AppSettings\ServiceProvider;

class TestCase extends \Squadron\Tests\TestCase
{
    protected function getServiceProviders(): array
    {
        return [ServiceProvider::class];
    }
}
