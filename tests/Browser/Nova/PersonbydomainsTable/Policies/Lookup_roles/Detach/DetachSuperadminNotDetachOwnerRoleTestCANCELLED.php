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

namespace Tests\Browser\Nova\PersonbydomainsTable\Policies\Detach\Lookup_roles;

// LaSalle Software
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class DetachSuperadminNotDetachOwnerRoleTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Superadminstrators cannot delete owners role
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesLookuproles
     * @group novaPersonbydomainPoliciesLookuprolesDetach
     * @group novaPersonbydomainPoliciesLookuprolesDetachSuperadminnotdetachownerrole
     */
    public function testSuperadminNotDetachOwnerRole()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Policies\Lookup_roles\Detach\TestDetachSuperadminNotDetachOwnerRole**";

        // Arrange
        // need an existing personbydomain to be an owner, because there is only one owner right now and that is the first
        // personbydomains. The first personbydomains and its row in the personbydomain_lookup_role is not delete-able.
        DB::table('personbydomain_lookup_roles')->where('id', 2)->update(['lookup_role_id' => 1]);

        $personTryingToLogin  = $this->loginSuperadminDomain1;
        $pause                = $this->pause;

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
                ->clickLink('Personbydomain')
                ->waitFor('@2-row')
                ->pause($pause['shortest'])
                ->assertVisible('@2-view-button')
                ->click('@2-view-button')
                ->pause($pause['short'])
                ->assertSee('Personbydomain Details')
                ->assertSee('Lookup User Role')
                ->assertVisible('@1-row')
                ->assertMissing('@1-delete-button')
            ;
        });
    }
}