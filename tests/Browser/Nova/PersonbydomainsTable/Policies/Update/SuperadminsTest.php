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

namespace Tests\Browser\Nova\PersonbydomainsTable\Policies\Update;

// LaSalle Software
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SuperadminsTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the super admin can edit non-owners (superadmins & admins) who belong to their domain
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesUpdate
     * @group novaPersonbydomainPoliciesUpdateSuperadmins
     * @group novaPersonbydomainPoliciesUpdateSuperadminsScenarioa
     */
    public function testSuperadminsScenarioA()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Policies\Update\TestSuperadmins**";

        // Arrange
        // Change the domains test users belong to
        $this->updateInstalleddomainid();

        $personTryingToLogin  = $this->loginSuperadminDomain1;
        $pause                = $this->pause();

        // Act, Assert
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->assertSee('Lookup User Roles')  // just an added assert that this menu item is visible in the sidebar
                ->clickLink('Personbydomains')
                ->pause($pause['long'])

                ->assertMissing('@1-edit-button')  // although this is the domain the super admin belongs to,
                                                           // a super admin should not be editing an owner
                ->assertMissing('@2-edit-button')  // not the domain the super admin belongs to
                ->assertMissing('@3-edit-button')  // not the domain the super admin belongs to
                ->assertVisible('@4-edit-button')  // this is the logged in user!
                ->assertMissing('@5-edit-button')  // not the domain the super admin belongs to
            ;
        });
    }

    /**
     * Test that the super admin cannot forbidden personbydomain records via direct URL
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesUpdate
     * @group novaPersonbydomainPoliciesUpdateSuperadmins
     * @group novaPersonbydomainPoliciesUpdateSuperadminsScenariob
     */
    public function testSuperadminsScenarioB()
    {
        // Arrange
        // Change the domains test users belong to
        $this->updateInstalleddomainid();

        $personTryingToLogin  = $this->loginSuperadminDomain1;
        $pause                = $this->pause();

        // Act, Assert
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->visit('/nova/resources/personbydomains/1/edit?viaResource=&viaResourceId=&viaRelationship=')
                ->pause($pause['long'])
                ->assertPathIs('/nova/403')
            ;
        });
    }
}
