<?php

namespace Tests;

use DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
