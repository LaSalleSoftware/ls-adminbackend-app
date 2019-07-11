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

namespace Tests\Browser\Nova\ProfileTables\EmailsTable\Creation;

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
    protected $newEmailTableData;
    protected $updatedEmailTableData;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->newEmailTableData = [
            'lookup_email_type_id'    => 4,
            'lookup_email_type_title' => 'Other',
            'email_address'           => 'satchmo@wonderful.com',
            'description'             => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim metus',
            'comments'                => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        ];
    }

    /**
     * Test that the email creation is successful
     *
     * @group nova
     * @group novaprofiletables
     * @group novaemail
     * @group novaemailcreationissuccessful
     */
    public function testCreateNewRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\EmailsTable\Creation\IsSuccessfulTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $newEmailTableData   = $this->newEmailTableData;
        $pause               = $this->pause;

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $newEmailTableData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Email Addresses')
                ->pause($pause['shortest'])
                ->assertSee('Create Email Address')
                ->clickLink('Create Email Address')
                ->pause($pause['short'])
                ->assertSee('Create Email Address')
                ->assertSelectHasOptions('@lookup_email_type', [1,2,3,4])
                ->type('@email_address', $newEmailTableData['email_address'])
                ->select('@lookup_email_type', $newEmailTableData['lookup_email_type_id'])
                ->type('@description', $newEmailTableData['description'])
                ->type('@comments', $newEmailTableData['comments'])
                ->click('@create-button')
                ->pause($pause['short'])
                ->assertSee('Email Address Details')
            ;

            $email = Email::orderBy('id', 'desc')->first();
            $uuid  = $this->getSecondLastUuidId();

            $browser->assertPathIs('/nova/resources/emails/'.$email->id);
            $this->assertEquals($newEmailTableData['email_address'],        $email->email_address);
            $this->assertEquals($newEmailTableData['lookup_email_type_id'], $email->lookup_email_type_id);
            $this->assertEquals($newEmailTableData['description'],          $email->description);
            $this->assertEquals($newEmailTableData['comments'],             $email->comments);

            $this->assertEquals($uuid->uuid, $email->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 7);
        });

        // these assertions are not needed, but still getting a feel for Dusk so here they are
        $this->assertDatabaseHas('emails', ['email_address' => $newEmailTableData['email_address']]);
        $this->assertDatabaseHas('emails', ['description'   => $newEmailTableData['description']]);
    }
}
