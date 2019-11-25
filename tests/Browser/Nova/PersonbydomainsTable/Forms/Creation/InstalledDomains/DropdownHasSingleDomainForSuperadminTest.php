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

namespace Tests\Browser\Nova\PersonbydomainsTable\Forms\Creation\InstalledDomains;

// LaSalle Software classes
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class DropdownHasSingleDomainForSuperadminTest extends PersonbydomainsTableBaseDuskTest
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
     * Test that installed domains dropdown contains only the domain belonging to the super admin user.
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainForms
     * @group novaPersonbydomainFormsCreate
     * @group novaPersonbydomainFormsCreateDomains
     * @group novaPersonbydomainFormsCreateDomainsDropdownhassingledomainforsuperadmin
     */
    public function testDomainDropdownHasMultipleDomainsForOwner()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Forms\Creation\InstalledDomains\DropdownHasSingleDomainForSuperadminTest**";

        $personTryingToLogin = $this->loginOwnerBobBloom;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
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
                ->assertSelectHasOption('@installed_domain', 1)
            ;
        });
    }
}
