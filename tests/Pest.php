<?php

use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test is bound in this context. Pest
| uses the TestCase from Laravel for Feature tests and the base
| PHPUnit TestCase for Unit tests.
|
*/

uses(TestCase::class)->in('Feature');
