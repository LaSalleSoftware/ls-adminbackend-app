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
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\PersonbydomainsTable\Forms\Update\Emails;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Email;
use Lasallesoftware\Library\Profiles\Models\Person;
use Lasallesoftware\Library\Profiles\Models\Person_email;
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class NewEmailTest extends PersonbydomainsTableBaseDuskTest
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
     * Test that a personbydomain is created with an email that is not yet in the emails database table
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainForms
     * @group novaPersonbydomainFormsUpdate
     * @group novaPersonbydomainFormsUpdateEmails
     * @group novaPersonbydomainFormsUpdateEmailsNewemail
     */
    public function testNewEmail()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Forms\Update\Emails\NewEmailTest**";

        // HAVE TO THINK UP A UNIQUE BRAND NEW EMAIL ADDRESS, AND THEN ADD THAT TO THE APP's SERVICE PROVIDER!!!!!!!
        // THEN PUT THIS NEW EMAIL ADDRESS IN THE PERSONBYDOMAINTABLEBASEDUSKTEST.php OOOOYYYY@!!!!!

        $personTryingToLogin                 = $this->loginOwnerBobBloom;
        $newEmailListedInNovaServiceProvider = $this->newEmailListedInNovaServiceProvider;
        $pause                               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $newEmailListedInNovaServiceProvider, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomains')
                ->pause($pause['long'])
                ->pause($pause['long'])
                ->assertVisible('@1-row')
                ->assertVisible('@1-edit-button')
                ->click('@1-edit-button')
                ->pause($pause['long'])
                ->assertSee('Update Personbydomain')
                ->type('@email', $newEmailListedInNovaServiceProvider['email_address'])
                ->click('@update-button')
                ->pause($pause['long'])
                ->assertSee('Personbydomain Details')
            ;
        });

        $uuid   = $this->getSecondLastUuidId()->uuid;

        // the new email should be in the emails database table
        $email = Email::where('email_address', $newEmailListedInNovaServiceProvider['email_address'])->first();
        $this->assertDatabaseHas('emails', ['email_address' => 'srv@doubletrouble.com']);
        $this->assertDatabaseHas('emails', ['description'   => 'Created by a personbydomain model event.']);
        $this->assertDatabaseHas('emails', ['comments'      => 'Created by a personbydomain model event.']);
        $this->assertDatabaseHas('emails', ['uuid'          => $uuid]);

        $this->assertEquals($email->lookup_email_type_id, $newEmailListedInNovaServiceProvider['lookup_email_type_id']);
        $this->assertEquals($email->email_address,        $newEmailListedInNovaServiceProvider['email_address']);
        $this->assertEquals($email->description,          'Created by a personbydomain model event.');
        $this->assertEquals($email->comments,             'Created by a personbydomain model event.');
        $this->assertEquals($email->uuid,                 $uuid);


        // the new email should be in the person_email database table
        $person_email = Person_email::where('email_id', $email->id)->first();
        $this->assertDatabaseHas('person_email', ['person_id' => $person_email->person_id]);
        $this->assertDatabaseHas('person_email', ['email_id'  => $person_email->email_id]);

        // check that the record exists, as the above "assertDatabaseHas" does not assure that the ID's reside in the same record.
        $person = Person::find(2);
        $person_email = DB::table('person_email')
            ->where('person_id', $person->id)
            ->where('email_id',  $email->id)
            ->first()
        ;

        $this->assertEquals($person->id, $person_email->person_id);
        $this->assertEquals($email->id,  $person_email->email_id);
    }
}
