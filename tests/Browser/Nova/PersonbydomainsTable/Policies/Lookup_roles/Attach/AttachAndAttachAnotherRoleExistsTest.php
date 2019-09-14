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

namespace Tests\Browser\Nova\PersonbydomainsTable\Policies\Lookup_roles\Attach;

// LaSalle Software
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class AttachAndAttachAnotherRoleExistsTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * SEE THE NOTE IN "AttachAndAttachAnotherNoRolesTest.php".
     *
     * This test, there is already a record in personbydomain_roles. So, the drop-down should display NOTHING!!
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesLookuproles
     * @group novaPersonbydomainPoliciesLookuprolesAttach
     * @group novaPersonbydomainPoliciesLookuprolesAttachAttachandattachanotherroleexists
     */
    public function testAttachAndAttachAnotherRoleExists()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Policies\Lookup_roles\Attach\TestAttachAndAttachAnotherRoleExists**";

        $personTryingToLogin  = $this->loginOwnerBobBloom;
        $pause                = $this->pause();

        // Act and Assert
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
                ->assertMissing(('@attach-button'))

                ->visit('/nova/resources/personbydomains/2/attach/lookup_roles?viaRelationship=lookup_role&polymorphic=0')  // direct URL because the attach button is not displaying
                ->pause($pause['short'])
                ->assertSelectMissingOptions('@attachable-select', [1,2,3])
            ;
        });
    }
}
