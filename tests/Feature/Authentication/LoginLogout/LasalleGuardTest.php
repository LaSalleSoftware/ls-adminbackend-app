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


class LasalleGuardTest extends TestCase
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
     * HTTP test that that lasalleguard::user() returns a authenticatable user object when user is logged in
     *
     * @group login
     * @group lasalleguard
     *
     * @return void
     */
    public function test_the_user_method_returns_a_user_when_a_user_is_logged_in()
    {
        echo "\n**Now testing Tests\Feature\Authentication\LoginLogout\LasalleGuardTest**";

        // A user has to be logged in for lasalleguard::user() to return a user!
        $response = $this
            ->createUuid()                  // The first thing the login controller does is create the UUID
            ->loginBobWithLoginMethod()     // Log me in! Go straight to the heart of the matter and log me via LasalleGuard::login()
            ->seeWhatUserMethodReturns()
            ->get('/home')                  // Redirected to "home" when successfully logged in
        ;

        $this->assertTrue(! is_null($this->user));
        $this->assertTrue($this->user instanceof \Illuminate\Contracts\Auth\Authenticatable );
    }

    /**
     * HTTP test that that lasalleguard::user() returns null when not logged in
     *
     * @group login
     * @group lasalleguard
     *
     * @return void
     */
    public function test_the_user_method_returns_null_when_no_user_is_logged_in()
    {
        $response = $this
            ->createUuid()
            ->seeWhatUserMethodReturns()
            ->get('/home')                  // Redirected to "home" when successfully logged in
        ;

        $this->assertTrue(is_null($this->user));
    }

    /**
     * HTTP test that that lasalleguard::user() returns a authenticatable user object when user is logged in
     *
     * @group login
     * @group lasalleguard
     *
     * @return void
     */
    public function test_the_id_method_returns_a_personbydomain_id_when_a_user_is_logged_in()
    {
        // A user has to be logged in for lasalleguard::user() to return a user!
        $response = $this
            ->createUuid()                  // The first thing the login controller does is create the UUID
            ->loginBobWithLoginMethod()     // Log me in! Go straight to the heart of the matter and log me via LasalleGuard::login()
            ->seeWhatIdMethodReturns()
            ->get('/home')                  // Redirected to "home" when successfully logged in
        ;

        $this->assertTrue(! is_null($this->id));
        $this->assertTrue(is_int($this->id));
        $this->assertTrue($this->id == 1 );
    }

    /**
     * HTTP test that lasalleguard::user() returns null when not logged in
     *
     * @group login
     * @group lasalleguard
     *
     * @return void
     */
    public function test_the_id_method_returns_null_when_no_user_is_logged_in()
    {
        $response = $this
            ->seeWhatIdMethodReturns()
            ->get('/home')                  // Redirected to "home" when successfully logged in
        ;

        $this->assertTrue(is_null($this->id));
    }

    /**
     * HTTP test that lasalleguard::loginUsingId() successfully logs in
     *
     * @group login
     * @group lasalleguard
     */
    public function test_the_loginusingid_method_should_successfully_login()
    {
        $id = 1;

        $response = $this
            ->createUuid()
            ->seeWhatLoginusingidMethodReturns($id)
            ->get('/home')
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
        $record = \Lasallesoftware\Library\Authentication\Models\Login::orderBy('created_at', 'desc')->first();
        $this->assertTrue($record->personbydomain_id == $this->app['session']->get($this->getTheNameFromTheGetNameMethod()),'***The login personbydomain_id should be 1***');
        $this->assertTrue($record->token == $this->app['session']->get('loginToken'),'***The login token is wrong***');
        //$this->assertTrue($record->uuid == UUIDGENERATORUUID,'***The uuid is wrong***');
        $this->assertTrue($record->created_at <> null,'***The created_at is wrong***');
        $this->assertTrue($record->created_by == 1,'***The created_by should be 1***');
        // just because we can, see if these two fields are integers
        $this->assertTrue(is_int($record->personbydomain_id), "***The personbydomain_id must be an integer***");
        $this->assertTrue(is_int($record->created_by), "***The created_by must be an integer***");

        // When I am successfully logged in, the UUID inserted into the uuids table should be the same as the UUID constant
        $uuid = \Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid::find(2);
        $this->assertTrue($record->uuid == $GLOBALS['uuid_generator_uuid'],'***The two uuids do not match***');
    }
}
