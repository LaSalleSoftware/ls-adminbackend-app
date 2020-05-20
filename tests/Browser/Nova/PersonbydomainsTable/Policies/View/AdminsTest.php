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

class AdminsTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that admins can view their own user in the index listing
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesView
     * @group novaPersonbydomainPoliciesViewAdmins
     */
    public function testAdmins()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Policies\View\TestAdmins**";

        $personTryingToLogin  = $this->loginAdminDomain1;
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
                ->assertDontSee('Lookup User Roles')  // this menu item should not be visible in the sidebar
                ->clickLink('Personbydomain')
                ->pause($pause['long'])
                ->pause($pause['long'])
                ->assertDontSee('Create Personbydomain') // should not see the create button
                ->assertVisible('@5-row')
                ->assertMissing('@1-row')  // bob.bloom@lasallesoftware.ca, in the test seeding (actually, in the initial seeding)
                ->assertMissing('@2-row')  // bbking@kingofblues.com, in the test seeding
                ->assertMissing('@3-row')  // srv@doubletrouble.com, in the test seeding
                ->assertMissing('@4-row')  // sidney.bechet@blogtest.ca, in the test seeding
                ->assertSee('robert.johnson@blogtest.ca')
            ;
        });
    }
}
