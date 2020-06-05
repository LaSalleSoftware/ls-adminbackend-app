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
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\LookupTables\Policies\Lookup_lasallesoftware_events\Index;


// LaSalle Software
use Tests\Browser\Nova\LookupTables\LookupTablesBaseDuskTestCase;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminsTest extends LookupTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the an admin cannot see Lookup_lasallesoftware_events.
     *
     * Please note that the index listing is controlled by the resource's indexQuery() method!
     *
     * @group nova
     * @group novalookuptables
     * @group novaLookuptablesPolicies
     * @group novaLookuptablesPoliciesLookuplasallesoftwareevents
     * @group novaLookuptablesPoliciesLookuplasallesoftwareeventsIndex
     * @group novaLookuptablesPoliciesLookuplasallesoftwareeventsIndexAdmins
     */
    public function testAdmins()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\Policies\Lookuplasallesoftwareevents\Index\TestAdmins**";

        $login = $this->loginAdminDomain1;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])

                ->assertDontSee('Lookup LaSalle Software Events')         // Superadmins and Admins do not see lookup tables
                ->visit('/nova/resources/Lookup_lasallesoftware_events')
                ->pause($pause['long'])
                ->assertDontSee('Create Lookup LaSalle Software Event')
                ->assertDontSee('Data Seeding During Installation')
                ->assertDontSee('No Event Specified')
                ->assertDontSee('Created during testing')
                ->assertDontSee('Login Form')
                ->assertDontSee('Register Form')
                ->assertDontSee('Nova Form')
                ->assertDontSee('Nova Creation Form')
                ->assertDontSee('Nova Update Form')
            ;
        });
    }
}
