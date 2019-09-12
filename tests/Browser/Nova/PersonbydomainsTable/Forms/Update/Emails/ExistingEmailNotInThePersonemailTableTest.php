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

namespace Tests\Browser\Nova\PersonbydomainsTable\Forms\Update\Emails;

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
     * @group novaPersonbydomainFormsUpdate
     * @group novaPersonbydomainFormsUpdateEmails
     * @group novaPersonbydomainFormsUpdateEmailsExistingemailnotinthepersonemailtable
     */
    public function testLeavePasswordUnchangedIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Forms\Update\Emails\ExistingEmailNotInThePersonemailTableTest**";

        // ARRANGE!
        // Need a test email in the emails db table
        DB::table('emails')->insert([
            'lookup_email_type_id' => $this->newEmailListedInNovaServiceProvider['lookup_email_type_id'],
            'email_address'        => $this->newEmailListedInNovaServiceProvider['email_address'],
            'description'          => $this->newEmailListedInNovaServiceProvider['description'],
            'comments'             => $this->newEmailListedInNovaServiceProvider['comments'],
            'uuid'                 => $this->newEmailListedInNovaServiceProvider['uuid'],
            'created_at'           => now(),
            'created_by'           => 1,
        ]);

        // Grab the ID's of the test data
        $person_id = 2;
        $email_id  = DB::table('emails')->where('email_address', $this->newEmailListedInNovaServiceProvider['email_address'])->pluck('id')->first();


        // ACT AND ASSERT!
        $personTryingToLogin                 = $this->loginOwnerBobBloom;
        $newEmailListedInNovaServiceProvider = $this->newEmailListedInNovaServiceProvider;
        $pause                               = $this->pause;

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $newEmailListedInNovaServiceProvider, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomains')
                ->waitFor('@1-row')
                ->assertVisible('@1-row')
                ->assertVisible('@1-edit-button')
                ->click('@1-edit-button')
                ->pause($pause['medium'])
                ->assertSee('Update Personbydomain')
                ->type('@email', $newEmailListedInNovaServiceProvider['email_address'])
                ->click('@update-button')
                ->pause($pause['medium'])
                ->assertSee('Personbydomain Details')
            ;
        });


        // emails database table
        $email  = Email::where('email_address', $newEmailListedInNovaServiceProvider['email_address'])->first();

        $this->assertDatabaseHas('emails', ['email_address' => $newEmailListedInNovaServiceProvider['email_address']]);
        $this->assertDatabaseHas('emails', ['description'   => $newEmailListedInNovaServiceProvider['description']]);
        $this->assertDatabaseHas('emails', ['comments'      => $newEmailListedInNovaServiceProvider['comments']]);
        $this->assertDatabaseHas('emails', ['uuid'          => $newEmailListedInNovaServiceProvider['uuid']]);

        $this->assertEquals($email->lookup_email_type_id, $newEmailListedInNovaServiceProvider['lookup_email_type_id']);
        $this->assertEquals($email->email_address,        $newEmailListedInNovaServiceProvider['email_address']);
        $this->assertEquals($email->description,          $newEmailListedInNovaServiceProvider['description']);
        $this->assertEquals($email->comments,             $newEmailListedInNovaServiceProvider['comments']);
        $this->assertEquals($email->uuid,                 $newEmailListedInNovaServiceProvider['uuid']);


        // person_email database table --> POPULATED DURING THE NOVA FORM's PROCESSING
        // check that the id's exist in the same record.
        $person_email = DB::table('person_email')
            ->where('person_id', 2)
            ->where('email_id', $email->id)
            ->first()
        ;
        $this->assertEquals($person_id, $person_email->person_id);
        $this->assertEquals($email_id,  $person_email->email_id);
    }
}