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
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\PersonbydomainsTable\Policies\Index;


// ** WELL, THIS IS NOT ACTUALLY TESTING POLICIES, BUT THE NOVA RESOURCE SETTING. BUT THIS TEST IS STAYING IN THIS FOLDER ANYWAYS! **


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
     * Test that the super admin sees the index listing for Scenario A:
     *   * super admin belongs to domain 1
     *   * all personbydomains records belong to domain 1
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesIndex
     * @group novaPersonbydomainPoliciesIndexSuperadmins
     * @group novaPersonbydomainPoliciesIndexSuperadminsScenarioa
     */
    public function testSuperadminsScenarioA()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Policies\Index\TestSuperadmins**";

        // Arrange
        //$this->updateInstalleddomainid();

        $personTryingToLogin  = $this->loginSuperadminDomain1;
        $pause                = $this->pause;

        // Act, Assert
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
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
                ->assertVisible('@2-row')
                ->assertVisible('@3-row')
                ->assertVisible('@4-row')
                ->assertVisible('@5-row')
                ->assertDontSee('pretendfrontend.com')
                ->assertDontSee('anotherpretendfrontend.com')
            ;
        });
    }

    /**
     * Test that the super admin sees the index listing for Scenario B:
     *   * super admin belongs to domain 1
     *   * personbydomains records belong to a mix of domains
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesIndex
     * @group novaPersonbydomainPoliciesIndexSuperadmins
     * @group novaPersonbydomainPoliciesIndexSuperadminsScenariob
     */
    public function testSuperadminsScenarioB()
    {
        // Arrange
        // Change the domains test users belong to
        $this->updateInstalleddomainid();

        $personTryingToLogin  = $this->loginSuperadminDomain1;
        $pause                = $this->pause;

        // Act, Assert
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomains')
                ->waitFor('@1-row')

                ->assertVisible('@1-row')   // belongs to hackintosh.lsv2-adminbackend-app.com
                ->assertMissing('@2-row')   // belongs to pretendfrontend.com
                ->assertMissing('@3-row')   // belongs to pretendfrontend.com
                ->assertVisible('@4-row')   // belongs to hackintosh.lsv2-adminbackend-app.com
                ->assertMissing('@5-row')   // belongs to anotherpretendfrontend.com

                ->assertSee(env('LASALLE_APP_DOMAIN_NAME'))
                ->assertDontSee('pretendfrontend.com')
                ->assertDontSee('anotherpretendfrontend.com')
            ;
        });
    }
}
