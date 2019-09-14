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
use Tests\LaSalleDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UniqueValidationFailsTest extends LaSalleDuskTestCase
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
            'email_address' => 'srv@doubletrouble.com',
            'lookup_email_type_id'    => 1,
        ];
    }

    /**
     * Test that the email update fails when the email address is not unique
     *
     * @group nova
     * @group novaprofiletables
     * @group novaemail
     * @group novaemailupdateuniqueval
     */
    public function testUniqueValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\EmailsTable\Update\UniqueValidationFailsTest**";

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
                ->pause($pause['medium'])
                ->assertSee('Update Email Address')
                ->type('@email_address', $updatedEmailTableData['email_address'])
                ->click('@update-button')
                ->pause($pause['medium'])
                ->pause($pause['medium'])
                ->assertSee('The email address has already been taken')
            ;
        });
    }
}
