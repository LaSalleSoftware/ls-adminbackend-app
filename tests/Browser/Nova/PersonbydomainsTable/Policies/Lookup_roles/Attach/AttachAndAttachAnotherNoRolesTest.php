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

namespace Tests\Browser\Nova\PersonbydomainsTable\Policies\Lookup_roles\Attach;

// LaSalle Software
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class AttachAndAttachAnotherNoRolesTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Cannot suppress the "Attach & Attach Again" button.
     * And, can get to the attach form via direct URL.
     * When the personbydomain_id has no record in the personbydomain_lookup_roles db pivot table, then can "attach" (ie,
     * insert a new record).
     * When the personbydomain_id does have a record in the personbydomain_lookup_roles, then cannot attach! Because
     * right now one role per personbydomain_id.
     *
     * So... want to test this!
     * BTW, the direct URL is http://hackintosh.lsv2-adminbackend-app.com:8888/nova/resources/personbydomains/2/attach/lookup_roles?viaRelationship=lookup_role&polymorphic=0
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesLookuproles
     * @group novaPersonbydomainPoliciesLookuprolesAttach
     * @group novaPersonbydomainPoliciesLookuprolesAttachAttachandattachagainnoroles
     */
    public function testAttachAndAttachAgainNoRoles()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Policies\Lookup_roles\Attach\TestAttachAndAttachAnotherNoRoles**";

        // Arrange
        // want to see the "Attach" button, so need to remove a record from the personbydomain_lookup_roles pivot db table
        DB::table('personbydomain_lookup_roles')->where('id', 2)->delete();

        $personTryingToLogin  = $this->loginOwnerBobBloom;
        $pause                = $this->pause();

        // Act and Assert
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->assertSee('Personbydomains')

                ->clickLink('Personbydomain')
                ->pause($pause['long'])
                ->pause($pause['long'])
                ->assertVisible('@2-view-button')

                ->click('@2-view-button')
                ->pause($pause['long'])

                ->assertSee('Personbydomain Details')
                ->assertSee('Lookup User Role')
                ->assertVisible(('@attach-button'))

                ->click('@attach-button')
                ->pause($pause['short'])
                ->assertSelectHasOptions('@attachable-select', [1,2,3])
            ;
        });
    }
}
