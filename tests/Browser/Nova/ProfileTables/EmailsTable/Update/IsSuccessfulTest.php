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

namespace Tests\Browser\Nova\ProfileTables\EmailsTable\Update;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Email;
use Tests\Browser\LaSalleDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;


class IsSuccessfulTest extends LaSalleDuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $updatedEmailTableData;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->updatedEmailTableData = [
            'lookup_email_type_id'    => 1,
            'lookup_email_type_title' => 'Primary',
            'email_address'           => 'steviervaughan@doubletrouble.com',
            'description'             => 'Id diam vel quam elementum pulvinar etiam.',
            'comments'                => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas',
        ];
    }

    /**
     * Test that the emails table record update is successful.
     *
     * @group nova
     * @group novaprofiletables
     * @group novaemail
     * @group novaemaileditsuccessful
     */
    public function testEditExistingEmailRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\EmailsTable\Update\IsSuccessfulTest**";

        $personTryingToLogin   = $this->personTryingToLogin;
        $updatedEmailTableData = $this->updatedEmailTableData;
        $pause                 = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $updatedEmailTableData, $pause) {

            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Email Addresses')
                ->waitFor('@4-edit-button')
                ->assertSee('Email Address')
                ->assertVisible('@4-edit-button')
                ->click('@4-edit-button')
                ->pause($pause['short'])
                ->assertSee('Update Email Address')
                ->type('@email_address', $updatedEmailTableData['email_address'])
                ->select('@lookup_email_type', $updatedEmailTableData['lookup_email_type_id'])
                ->type('@description', $updatedEmailTableData['description'])
                ->type('@comments', $updatedEmailTableData['comments'])
                ->click('@update-button')
                ->pause($pause['short'])
                ->assertSee('Email Address Details')
            ;

            $email = Email::orderBy('id', 'desc')->first();
            $uuid  = $this->getSecondLastUuidId();

            $browser->assertPathIs('/nova/resources/emails/'.$email->id);
            $this->assertEquals($updatedEmailTableData['email_address'],    $email->email_address);
            $this->assertEquals($updatedEmailTableData['lookup_email_type_id'], $email->lookup_email_type_id);
            $this->assertEquals($updatedEmailTableData['description'],          $email->description);
            $this->assertEquals($updatedEmailTableData['comments'],             $email->comments);

            $this->assertEquals($uuid->uuid, $email->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 8);
        });

        // these assertions are not needed, but still getting a feel for Dusk so here they are
        $this->assertDatabaseHas('emails', ['email_address' => $updatedEmailTableData['email_address']]);
        $this->assertDatabaseHas('emails', ['description'   => $updatedEmailTableData['description']]);
    }
}
