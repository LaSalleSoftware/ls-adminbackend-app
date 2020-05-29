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

namespace Tests\Browser\Nova\PersonbydomainsTable\Policies\Delete;

// LaSalle Software
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

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
     * Test that super admins can delete records belonging to their domain
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesDelete
     * @group novaPersonbydomainPoliciesDeleteSuperadmins
     * @group novaPersonbydomainPoliciesDeleteSuperadminsScenarioa
     */
    public function testSuperadminsScenarioA()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Policies\Delete\TestSuperadmins**";

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
                ->assertSee('Personbydomains')
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomains')
                ->pause($pause['long'])
                ->assertMissing('@1-delete-button')  // Record #1 is in the "do not delete" array
                ->assertMissing('@2-delete-button')
                ->assertMissing('@3-delete-button')
                ->assertVisible('@4-delete-button')
                ->assertMissing('@5-delete-button')
            ;
        });
    }

    /**
     * Test that super admins cannot delete owners
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesDelete
     * @group novaPersonbydomainPoliciesDeleteSuperadmins
     * @group novaPersonbydomainPoliciesDeleteSuperadminsScenariob
     */
    public function testSuperadminsScenarioB()
    {
        // Arrange
        // Change the domains test users belong to
        $this->updateInstalleddomainid();

        // Change Blues Boy King (personbydomains id = 2) to an owner (first have to revert BB King back to installed_domain_id #1 --> revert 'em all!)
        DB::table('personbydomains')->whereIn('id', [2, 3, 4, 5])->update(['installed_domain_id' => 1, 'installed_domain_title' => env('LASALLE_APP_DOMAIN_NAME')]);
        DB::table('personbydomain_lookup_roles')->where('id', 2)->update(['lookup_role_id' => 1]);

        $personTryingToLogin  = $this->loginSuperadminDomain1;
        $pause                = $this->pause();

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
                ->waitFor('@4-row')

                ->assertMissing('@1-delete-button')  // Record #1 is in the "do not delete" array
                ->assertMissing('@2-delete-button')  // should be an owner!
                ->assertVisible('@3-delete-button')
                ->assertVisible('@4-delete-button')
                ->assertVisible('@5-delete-button')
            ;
        });
    }
}
