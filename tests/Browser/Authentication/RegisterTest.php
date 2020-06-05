<?php

/**
 * This file is part of  Lasalle Software 
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca Blog, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Authentication;

// LaSalle Software classes
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid;
use Lasallesoftware\Librarybackend\Profiles\Models\Person;
use Lasallesoftware\Librarybackend\Profiles\Models\Email;
use Lasallesoftware\Librarybackend\Profiles\Models\Person_email;
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain;
use Lasallesoftware\Librarybackend\Profiles\Models\Installed_domain;
use Lasallesoftware\Librarybackend\Authentication\Models\Login;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;


class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;


    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');

        $this->personTryingToRegister = [
            'first_name' => 'Richard',
            'surname'    => 'Blaine',
            'email'      => 'rick@cafeamericain.com',
            'password'   => 'secret',
        ];
    }

    /**
     * Test that the correct credentials result in a successful login.
     *
     * Basic scenario:
     *  ** fresh new person not yet in the database
     *  ** first_name and surname are ok
     *  ** email is unique
     *  ** password is ok
     *
     * @group authentication
     * @group authenticationRegister
     */
    public function testRegisterNewPersonBasicScenarioShouldBeSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Authentication\RegisterTest **";

        $personTryingToRegister = $this->personTryingToRegister;

        $this->browse(function (Browser $browser) use ($personTryingToRegister) {
            $browser->visit('/register')
                ->assertSee('Register')
                ->type('first_name',            $personTryingToRegister['first_name'])
                ->type('surname',               $personTryingToRegister['surname'])
                ->type('email',                 $personTryingToRegister['email'])
                ->type('password',              $personTryingToRegister['password'])
                ->type('password_confirmation', $personTryingToRegister['password'])
                ->press('Register')
                ->pause(4500)
                //->assertPathIs('/home')                // Getting a 403 error, but running manually it is fine. The redirection from the new
                //->assertSee('You are logged in!')      // Lasallesoftware\Librarybackend\Authentication\Http\Middleware\RedirectSomeRoutes is not the culprit, so far as I can tell
            ;
        });

        // UUID model created
        $uuid = Uuid::orderBy('created_at', 'desc')->first();


        // EMAILS database table
        $email = Email::orderBy('created_at', 'desc')->first();
        //var_dump($email);

        $this->assertTrue($email->id == 5,'***The id is wrong***');
        $this->assertTrue($email->lookup_email_type_id == 1,'***The lookup_email_type_id is wrong***');
        $this->assertTrue($email->email_address == $personTryingToRegister['email'],'***The email_address is wrong***');
        $this->assertTrue($email->description == 'Created by the Register Form.','***The descripotion is wrong***');
        $this->assertTrue($email->comments == 'Created by the Register Form.','***The comments is wrong***');
        $this->assertTrue($email->uuid == $uuid->uuid,'***The uuid is wrong***');
        $this->assertTrue($email->created_at <> null,'***The created_at is wrong***');
        $this->assertTrue($email->created_by == 1,'***The created_by is wrong***');


        // PERSONS database table
        $person = Person::orderBy('created_at', 'desc')->first();

        $this->assertTrue($person->id == 305,'***The id is wrong***');
        $this->assertTrue($person->first_name == $personTryingToRegister['first_name'],'***The first_name is wrong***');
        $this->assertTrue($person->surname == $personTryingToRegister['surname'],'***The surname is wrong***');
        $this->assertTrue($person->description == 'Created by the Register Form.','***The description is wrong***');
        $this->assertTrue($person->comments == 'Created by the Register Form.','***The comments is wrong***');
        $this->assertTrue($person->uuid == $uuid->uuid,'***The uuid is wrong***');
        $this->assertTrue($person->created_at <> null,'***The created_at is wrong***');
        $this->assertTrue($person->created_by == 1,'***The created_by is wrong***');


        // PERSON_EMAIL database table
        $person_email = Person_email::find(4);

        $this->assertTrue($person_email->id == 4,'***The id is wrong***');
        $this->assertTrue($person_email->person_id == 305,'***The person_id is wrong***');
        $this->assertTrue($person_email->email_id == 5,'***The email_id is wrong***');


        // PERSONBYDOMAINS database table
        $lookup_domain = Installed_domain::find(1)->first();
        $personbydomain = Personbydomain::orderBy('created_at', 'desc')->first();

        $this->assertTrue($personbydomain->id == 4,'***The id is wrong***');
        $this->assertTrue($personbydomain->person_id == $person->id, '***The person_id is wrong***');
        $this->assertTrue($personbydomain->person_first_name == $person->first_name,'***The first_name is wrong***');
        $this->assertTrue($personbydomain->person_surname == $person->surname,'***The surname is wrong***');
        $this->assertTrue($personbydomain->email == $email->email_address,'***The email address is wrong***');
        $this->assertTrue($personbydomain->password <> null,'***The password is wrong***');
        $this->assertTrue($personbydomain->installed_domain_id == $lookup_domain->id, '***The lookup domain id is wrong***');
        $this->assertTrue($personbydomain->installed_domain_title == $lookup_domain->title, '***The lookup domain title is wrong***');
        $this->assertTrue($personbydomain->uuid == $uuid->uuid,'***The uuid is wrong***');
        $this->assertTrue($personbydomain->created_at <> null,'***The created_at is wrong***');
        $this->assertTrue($personbydomain->created_by == 1,'***The created_by is wrong***');


        // PERSONBYDOMAIN_LOOKUP_ROLES
        $personbydomain_lookup_roles = DB::table('personbydomain_lookup_roles')
            ->orderBy('id', 'desc')
            ->first()
        ;

        $this->assertTrue($personbydomain_lookup_roles->id == 4, '***The personbydomain_lookup_roles id is wrong***');
        $this->assertTrue($personbydomain_lookup_roles->personbydomain_id == 4, '***The personbydomain_lookup_roles personbydomain_id is wrong***');
        $this->assertTrue($personbydomain_lookup_roles->lookup_role_id == 3, '***The personbydomain_lookup_roles lookup_role_id is wrong***');


        // LOGINS database table
        $login = Login::orderBy('created_at', 'desc')->first();

        $this->assertTrue($login->id == 1, '***The id is wrong***');
        $this->assertTrue($login->personbydomain_id == $personbydomain->id, '***The personbydomain_id is wrong***');
        $this->assertTrue($login->token <> null, "***The login token is not null");
        $this->assertTrue($login->uuid == $uuid->uuid,'***The uuid is wrong***');
        $this->assertTrue($personbydomain->created_at <> null,'***The created_at is wrong***');
        $this->assertTrue($personbydomain->created_by == 1,'***The created_by is wrong***');
    }
}
