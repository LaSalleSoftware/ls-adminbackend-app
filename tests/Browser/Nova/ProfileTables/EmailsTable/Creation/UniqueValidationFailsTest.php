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
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UniqueValidationFailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $newEmailTableData;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->newEmailTableData = [
            'lookup_email_type_id'    => 1,
            'lookup_email_type_title' => 'Primary',
            'email_address'           => 'bob.bloom@lasallesoftware.ca',
            'description'             => '',
            'comments'                => '',
        ];
    }

    /**
     * Test that the email creation fails when the email address is not unique
     *
     * @group nova
     * @group novaemail
     * @group novaemailcreationuniqueval
     */
    public function testUniqueValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\EmailsTable\Creation\UniqueValidationFailsTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $newEmailTableData   = $this->newEmailTableData;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $newEmailTableData) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Email Addresses')
                ->pause(500)
                ->assertSee('Create Email Address')
                ->clickLink('Create Email Address')
                ->pause(2000)
                ->assertSee('New Email Address')
                ->assertSelectHasOptions('@lookup_email_type', [1,2,3,4])
                ->type('@email_address', $newEmailTableData['email_address'])
                ->select('@lookup_email_type', $newEmailTableData['lookup_email_type_id'])
                ->click('@create-button')
                ->pause(500)
                ->assertSee('The email address has already been taken')
            ;
        });
    }
}
