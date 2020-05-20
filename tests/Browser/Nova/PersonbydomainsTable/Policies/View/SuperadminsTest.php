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

namespace Tests\Browser\Nova\PersonbydomainsTable\Policies\View;

// LaSalle Software
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SuperadminsTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that super admins can view personbydomains that belong to the superadmin's domain.
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesView
     * @group novaPersonbydomainPoliciesViewSuperadmins
     */
    public function testSuperadmins()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Policies\View\TestSuperadmins**";

        // Arrange
        // Change the domains test users belong to
        $this->updateInstalleddomainid();

        $personTryingToLogin  = $this->loginSuperadminDomain1;
        $pause                = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->assertSee('Lookup User Roles')  // just an added assert that this menu item is visible in the sidebar
                ->clickLink('Personbydomains')
                ->pause($pause['short'])
                ->assertVisible('@1-view-button')
                ->assertMissing('@2-view-button')  // should belong to pretendfrontend.com
                ->assertMissing('@3-view-button')  // should belong to pretendfrontend.com
                ->assertVisible('@4-view-button')
                ->assertMissing('@5-view-button')  // should belong to anotherpretendfrontend.com
            ;
        });
    }
}
