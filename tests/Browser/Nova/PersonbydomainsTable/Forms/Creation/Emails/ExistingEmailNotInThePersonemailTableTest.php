<?php

/**
 * This file is part of the Lasalle Software library (lasallesoftware/library)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\PersonbydomainsTable\Forms\Creation\Emails;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Email;
use Lasallesoftware\Library\Profiles\Models\Person;
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class ExistingEmailNotInThePersonemailTableTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        // Yes, I am using the blog seeds!
        $this->artisan('lslibrary:customseed');
        $this->artisan('lslibrary:installeddomainseed');
    }

    /**
     * Test that a personbydomain is created with an email that is in emails db table, but not in the person_email database table.
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainForms
     * @group novaPersonbydomainFormsCreate
     * @group novaPersonbydomainFormsCreateEmails
     * @group novaPersonbydomainFormsCreateEmailsExistingEmailNotinthepersonemailtable
     */
    public function testExistingEmailNotInThePersonemailTable()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Forms\Creation\Emails\ExistingEmailNotInThePersonemailTableTest**";

        // ARRANGE!
        // Need a test persons who is not in the personbydomains db table, and not faker generated.
        // So let's create one!
        $this->insertTestRecordIntoPersonsTable();

        // Need a test email in the emails and person_email database tables
        $this->insertTestRecordIntoEmailsTable();

        // Grab the ID's of the test data
        $personId = DB::table('persons')->where('name_calculated', $this->newPersonData['name_calculated'])->pluck('id')->first();
        $emailId  = DB::table('emails')->where('email_address', $this->newEmailData['email_address'])->pluck('id')->first();


        // ACT AND ASSERT!
        $personTryingToLogin = $this->loginOwnerBobBloom;
        $newEmailData        = $this->newEmailData;
        $newPersonData       = $this->newPersonData;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $newEmailData, $newPersonData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomains')
                ->pause($pause['long'])
                ->pause($pause['long'])
                ->assertSee('Create Personbydomain')
                ->clickLink('Create Personbydomain')
                ->pause($pause['long'])
                ->assertSee('Create Personbydomain')
                ->type('@email', $newEmailData['email_address'])
                ->type('@password', 'secret')
                ->type('@password_confirmation', 'secret')
                ->select('@installed_domain', 1)

                // The following is required to cope with the drop-down being searchable. So we cannot use the usual
                // ->select('@posts', 2). Instead, we have to go through the literal keystrokes.
                // Thank you to https://github.com/laravel/nova-dusk-suite/blob/10e02ff765a37771ae6436c112b93f6dab1819b9/tests/Browser/Pages/HasSearchableRelations.php
                ->click('[dusk="people-search-input"]')
                ->pause($pause['long'])
                ->type('[dusk="people-search-input"] input', $newPersonData['name_calculated'])
                ->pause($pause['long'])
                ->keys('[dusk="people-search-input"] input', ['{enter}'])

                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('Personbydomain Details')
            ;
        });


        // persons database table
        $person = Person::where('name_calculated', $newPersonData['name_calculated'])->first();
        $this->assertDatabaseHas('persons', ['name_calculated' => $this->newPersonData['name_calculated']]);
        $this->assertDatabaseHas('persons', ['first_name'      => $this->newPersonData['first_name']]);
        $this->assertDatabaseHas('persons', ['surname'         => $this->newPersonData['surname']]);
        $this->assertDatabaseHas('persons', ['uuid'            => 'created during a Dusk test']);
        $this->assertDatabaseHas('persons', ['created_by'      => 1]);

        $this->assertEquals($person->name_calculated, $this->newPersonData['name_calculated']);
        $this->assertEquals($person->first_name,      $this->newPersonData['first_name']);
        $this->assertEquals($person->surname,         $this->newPersonData['surname']);
        $this->assertEquals($person->uuid,            $this->newPersonData['uuid']);
        $this->assertEquals($person->created_by,      $this->newPersonData['created_by']);


        // emails database table
        $email  = Email::where('email_address',    $newEmailData['email_address'])->first();
        $this->assertDatabaseHas('emails', ['email_address' => $newEmailData['email_address']]);
        $this->assertDatabaseHas('emails', ['description'   => $newEmailData['description']]);
        $this->assertDatabaseHas('emails', ['comments'      => $newEmailData['comments']]);
        $this->assertDatabaseHas('emails', ['uuid'          => $newEmailData['uuid']]);

        $this->assertEquals($email->lookup_email_type_id, $newEmailData['lookup_email_type_id']);
        $this->assertEquals($email->email_address,        $newEmailData['email_address']);
        $this->assertEquals($email->description,          $newEmailData['description']);
        $this->assertEquals($email->comments,             $newEmailData['comments']);
        $this->assertEquals($email->uuid,                 $newEmailData['uuid']);


        // person_email database table --> POPULATED DURING THE NOVA FORM's PROCESSING
        // check that the record exists in the same record.
        $person_email = DB::table('person_email')
            ->where('person_id', $person->id)
            ->where('email_id', $email->id)
            ->first()
        ;
        $this->assertEquals($personId, $person_email->person_id);
        $this->assertEquals($emailId,  $person_email->email_id);
    }
}
