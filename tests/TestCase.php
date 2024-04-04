<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected function setUp(): void
    {
        parent::setUp();

        // $this->artisan('passport:install --force');
        // DB::table('oauth_clients')->insert([
        //     'id' => '9bb99544-8b0f-428f-96c1-ec78bbe81956',
        //     'user_id' => null,
        //     'name' => 'Laravel Personal Access Client',
        //     'secret' => 'NlGaT640z2AG6eNIkfsJzo1OedUkTJjvi4c9w4ut',
        //     'provider' => null,
        //     'redirect' => 'http://localhost',
        //     'personal_access_client' => 1,
        //     'password_client' => 0,
        //     'revoked' => 0,
        //     'created_at' => date('Y-m-d'),
        //     'updated_at' => date('Y-m-d'),
        // ]);






        $this->withoutExceptionHandling();
    }
}
