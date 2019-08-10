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

namespace Tests\Browser\Nova\PersonbydomainsTable\Forms\Delete;

// LaSalle Software classes
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;


class IsSuccessfulTest extends PersonbydomainsTableBaseDuskTest
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
     * Test that a personbydomain is successfully deleted. Using an owner user.
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainForms
     * @group novaPersonbydomainFormsDelete
     * @group novaPersonbydomainFormsDeleteIssuccessful
     */
    public function testIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Forms\Delete\IsSuccessfulTest**";

        // Need a test persons who is not in the personbydomains db table, and not faker generated.
        // So let's create one!

        $this->insertTestRecordIntoEmailsTable();
        $this->insertTestRecordIntoPersonsTable();
        $this->insertTestRecordIntoPerson_emaiTable();
        $this->insertTestRecordIntoPersonbydomainTable();
        $personbydomainLastId = DB::table('personbydomains')->orderBy('id', 'desc')->pluck('id')->first();

        $personTryingToLogin  = $this->loginOwnerBobBloom;
        $pause                = $this->pause;

        $this->browse(function (LaSalleBrowser $browser) use ($personbydomainLastId, $personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomains')
                ->pause($pause['shortest'])
                ->waitFor('@'.$personbydomainLastId.'-row')
                ->click('@'.$personbydomainLastId.'-delete-button')
                ->pause($pause['shortest'])
                ->click('#confirm-delete-button')
                ->pause($pause['medium'])
                ->assertMissing('@'.$personbydomainLastId.'-row')
            ;
        });

        // the deleted record from the personbydomains db table should not exist
        $this->assertDatabaseMissing('personbydomains', ['person_first_name' => $this->newPersonData['first_name']]);
        $this->assertDatabaseMissing('personbydomains', ['person_surname'    => $this->newPersonData['surname']]);

        // the newly inserted person should be in the persons database table
        $this->assertDatabaseHas('persons', ['name_calculated' => $this->newPersonData['name_calculated']]);
        $this->assertDatabaseHas('persons', ['first_name'      => $this->newPersonData['first_name']]);

        // the newly inserted email should be in the emails database table
        $this->assertDatabaseHas('emails', ['email_address' => $this->newEmailData['email_address']]);
        $this->assertDatabaseHas('emails', ['description'   => $this->newEmailData['description']]);
        $this->assertDatabaseHas('emails', ['comments'      => $this->newEmailData['comments']]);

        // the newly inserted person_email record should exist in the person_email database table
        $person_email = DB::table('person_email')->orderBy('id', 'desc')->first();
        $this->assertDatabaseHas('person_email', ['id'        => $person_email->id]);
        $this->assertDatabaseHas('person_email', ['person_id' => $person_email->person_id]);
        $this->assertDatabaseHas('person_email', ['email_id'  => $person_email->email_id]);
    }
}
