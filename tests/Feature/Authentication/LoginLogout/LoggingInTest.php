<?php

namespace Tests\Feature\Authentication\LoginLogout;

// LaSalle Software
use Lasallesoftware\Librarybackend\Testing\Concerns\Auth\InteractsWithAuthentication;
use Lasallesoftware\Librarybackend\Testing\Concerns\Uuid\InteractsWithUuid;

// Laravel
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class LoggingInTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;
    use InteractsWithAuthentication;
    use InteractsWithUuid;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrarybackend:customseed');
        $this->makeUuidgenerator();
    }

    /**
     * HTTP test that I login successfully using LasalleGuard's login method
     *
     * @group login
     * @group loggingintest
     * @group loggingintest_I_login_successfully_with_the_lasalleguard_login_method
     *
     * @return void
     */
    public function test_I_login_successfully_with_the_lasalleguard_login_method()
    {
        echo "\n**Now testing Tests\Feature\Authentication\LoginLogout\LoggingInTest**";

        // Simulate my logging in
        $response = $this
            ->createUuid()                  // The first thing the login controller does is create the UUID
            ->loginBobWithLoginMethod()     // Log me in! Go straight to the heart of the matter and log me via LasalleGuard::login()
            ->get('/home')                  // Redirected to "home" when successfully logged in
        ;

        // Was I redirected to "home" when I logged in?
        $response->assertStatus(200);
        $response->assertSee('You are logged in!');

        // When I am successfully logged in, there are two variables "put" to the session:
        // the loginToken
        $response->assertSessionHas('loginToken');
        // the personbydomain_id
        $response->assertSessionHas($this->getTheNameFromTheGetNameMethod());
        // just because we can, let's assert that the session has no errors
        $response->assertSessionHasNoErrors();

        // When I am successfully logged in, the logins table has a record:
        $record = \Lasallesoftware\Librarybackend\Authentication\Models\Login::orderBy('created_at', 'desc')->first();
        $this->assertTrue($record->personbydomain_id == $this->app['session']->get($this->getTheNameFromTheGetNameMethod()),'***The login personbydomain_id should be 1***');
        $this->assertTrue($record->token == $this->app['session']->get('loginToken'),'***The login token is wrong***');
        //$this->assertTrue($record->uuid == UUIDGENERATORUUID,'***The uuid is wrong***');
        $this->assertTrue($record->created_at <> null,'***The created_at is wrong***');
        $this->assertTrue($record->created_by == 1,'***The created_by should be 1***');

        // populating the updated records on login
        // https://github.com/LaSalleSoftware/lsv2-library-pkg/issues/20
        $this->assertTrue($record->updated_at <> null,'***The updated_at is wrong***');
        $this->assertTrue($record->updated_by == 1,'***The updated_by should be 1***');

        // just because we can, see if these two fields are integers
        $this->assertTrue(is_int($record->personbydomain_id), "***The personbydomain_id must be an integer***");
        $this->assertTrue(is_int($record->created_by), "***The created_by must be an integer***");
        $this->assertTrue(is_int($record->updated_by), "***The updated_by must be an integer***");

        // When I am successfully logged in, the UUID inserted into the uuids table should be the same as the UUID constant
        $uuid = \Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid::find(2);
        $this->assertTrue($record->uuid == $GLOBALS['uuid_generator_uuid'],'***The two uuids do not match***');
    }

    /**
     * HTTP test that I login successfully using LasalleGuard's attempt method
     *
     * @group login
     * @group loggingintest
     * @group loggingintest_I_login_successfully_with_the_lasalleguard_attempt_method
     *
     * @return void
     */
    public function test_I_login_successfully_with_the_lasalleguard_attempt_method()
    {
        $credentials =  [
            'email' => 'bob.bloom@lasallesoftware.ca',
            'password'              => 'secret',
        ];

        // Simulate my logging in
        $response = $this
            ->createUuid()                                // The first thing the login controller does is create the UUID
            ->loginBobWithAttemptMethod($credentials)     // Log me in! Go straight to the heart of the matter and log me via LasalleGuard::login()
            ->get('/home')                                // Redirected to "home" when successfully logged in
        ;

        // Was I redirected to "home" when I logged in?
        $response->assertStatus(200);
        $response->assertSee('You are logged in!');

        // When I am successfully logged in, there are two variables "put" to the session:
        // the loginToken
        $response->assertSessionHas('loginToken');
        // the personbydomain_id
        $response->assertSessionHas($this->getTheNameFromTheGetNameMethod());
        // just because we can, let's assert that the session has no errors
        $response->assertSessionHasNoErrors();

        // When I am successfully logged in, the logins table has a record:
        $record = \Lasallesoftware\Librarybackend\Authentication\Models\Login::orderBy('created_at', 'desc')->first();
        $this->assertTrue($record->personbydomain_id == $this->app['session']->get($this->getTheNameFromTheGetNameMethod()),'***The login personbydomain_id should be 1***');
        $this->assertTrue($record->token == $this->app['session']->get('loginToken'),'***The login token is wrong***');
        //$this->assertTrue($record->uuid == UUIDGENERATORUUID,'***The uuid is wrong***');
        $this->assertTrue($record->created_at <> null,'***The created_at is wrong***');
        $this->assertTrue($record->created_by == 1,'***The created_by should be 1***');
        $this->assertTrue($record->updated_at <> null,'***The updated_at is wrong***');
        $this->assertTrue($record->updated_by == 1,'***The updated_by should be 1***');
        // just because we can, see if these two fields are integers
        $this->assertTrue(is_int($record->personbydomain_id), "***The personbydomain_id must be an integer***");
        $this->assertTrue(is_int($record->created_by), "***The created_by must be an integer***");
        $this->assertTrue(is_int($record->updated_by), "***The updated_by must be an integer***");

        // When I am successfully logged in, the UUID inserted into the uuids table should be the same as the UUID constant
        $uuid = \Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid::find(2);
        $this->assertTrue($record->uuid == $GLOBALS['uuid_generator_uuid'],'***The two uuids do not match***');
    }

    /**
     * HTTP test that I login unsuccessfully with the wrong email address using LasalleGuard's attempt method
     *
     * @group login
     * @group loggingintest
     * @group loggingintestI_login_unsuccessfully_with_wrong_email_with_the_lasalleguard_attempt_method
     *
     * @return void
     */
    public function test_I_login_unsuccessfully_with_wrong_email_with_the_lasalleguard_attempt_method()
    {
        $credentials =  [
            'email'    => 'wrong@lemail.com',
            'password' => 'secret',
        ];

        // Simulate my logging in
        $response = $this
            ->createUuid()                                // The first thing the login controller does is create the UUID
            ->loginBobWithAttemptMethod($credentials)     // Log me in! Go straight to the heart of the matter and log me via LasalleGuard::login()
            ->get('/home')                                // Redirected to "home" when successfully logged in
        ;

        // Was I redirected?
        $response->assertStatus(302);

        // When I am successfully logged in, there are two variables "put" to the session:
        // the loginToken
        $response->assertSessionMissing('loginToken');
        // the personbydomain_id
        $response->assertSessionMissing($this->getTheNameFromTheGetNameMethod());
        // just because we can, let's assert that the session has no errors
        $response->assertSessionHasNoErrors();

        // The logins table should not have a record:
        $record = \Lasallesoftware\Librarybackend\Authentication\Models\Login::orderBy('created_at', 'desc')->first();
        $this->assertTrue(is_null($record));
    }

    /**
     * HTTP test that I login unsuccessfully with the wrong password using LasalleGuard's attempt method
     *
     * @group login
     * @group loggingintest
     * @group loggingintestI_login_unsuccessfully_with_wrong_paswword_with_the_lasalleguard_attempt_method
     *
     * @return void
     */
    public function test_I_login_unsuccessfully_with_wrong_paswword_with_the_lasalleguard_attempt_method()
    {
        $credentials =  [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'wrong_password',
        ];

        // Simulate my logging in
        $response = $this
            ->createUuid()                                // The first thing the login controller does is create the UUID
            ->loginBobWithAttemptMethod($credentials)     // Log me in! Go straight to the heart of the matter and log me via LasalleGuard::login()
            ->get('/home')                                // Redirected to "home" when successfully logged in
        ;

        // Was I redirected?
        $response->assertStatus(302);

        // When I am successfully logged in, there are two variables "put" to the session:
        // the loginToken
        $response->assertSessionMissing('loginToken');
        // the personbydomain_id
        $response->assertSessionMissing($this->getTheNameFromTheGetNameMethod());
        // just because we can, let's assert that the session has no errors
        $response->assertSessionHasNoErrors();

        // The logins table should not have a record:
        $record = \Lasallesoftware\Librarybackend\Authentication\Models\Login::orderBy('created_at', 'desc')->first();
        $this->assertTrue(is_null($record));
    }
}
