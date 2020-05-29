<?php

namespace Tests\Feature\Authentication\LoginLogout;

// LaSalle Software
use Lasallesoftware\Library\Testing\Concerns\Auth\InteractsWithAuthentication;
use Lasallesoftware\Library\Testing\Concerns\Uuid\InteractsWithUuid;

// Laravel
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class LoggingOutTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;
    use InteractsWithAuthentication;
    use InteractsWithUuid;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrary:customseed');
        $this->makeUuidgenerator();
    }

    /**
     * HTTP test that I login successfully using LasalleGuard's attempt method
     *
     * @group login
     * @group loggingouttest
     * @group logout
     *
     * @return void
     */
    public function test_I_logout_successfully_with_the_lasalleguard_logout_method()
    {
        echo "\n**Now testing Tests\Feature\Authentication\LoginLogout\LoggingOutTest**";

        // Simulate my logging in
        $response = $this
            ->createUuid()                  // The first thing the login controller does is create the UUID
            ->loginBobWithLoginMethod()     // Log me in! Go straight to the heart of the matter and log me via LasalleGuard::login()
            ->logoutBobWithLogoutMethod()   // Log me out!
            ->get('/home')                  // Redirected to "home" when successfully logged in
        ;

        // I should not be logged in
        $response->assertStatus(302);
        //$response->assertSee('login');

        // When I am successfully logged in, there are two variables "put" to the session:
        // the loginToken
        $response->assertSessionMissing('loginToken');
        // the personbydomain_id
        $response->assertSessionMissing($this->getTheNameFromTheGetNameMethod());
        // just because we can, let's assert that the session has no errors
        $response->assertSessionHasNoErrors();

        // The logins table should not have a record:
        $record = \Lasallesoftware\Library\Authentication\Models\Login::orderBy('created_at', 'desc')->first();
        $this->assertTrue(is_null($record));
    }
}
