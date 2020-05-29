<?php

/**
 * This file is part of  Lasalle Software .
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
 *
 * @see       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @see       https://packagist.org/packages/lasallesoftware/library Packagist
 * @see       https://github.com/lasallesoftware/library GitHub
 */

namespace Tests\Browser\Nova\InstalledDomainsTable\Policies\Delete;

// LaSalle Software
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;
// Laravel class
use Tests\Browser\Nova\InstalledDomainsTable\InstalledDomainsTableBaseDuskTestCase;

/**
 * @internal
 * @coversNothing
 */
class OwnersDomainOnlyTest extends InstalledDomainsTableBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lslibrary:installeddomainseed');
    }

    /**
     * Test that the an owner can not see the delete icons.
     *
     * Super admins and admins cannot see the index listing, so the delete icons are not accessible.
     *
     * @group nova
     * @group NovaInstalleddomainstable
     * @group NovaInstalleddomainstablePolicies
     * @group NovaInstalleddomainstablePoliciesDelete
     * @group NovaInstalleddomainstablePoliciesDeleteOwnersdomainonly
     */
    public function testOwnersDomainOnly()
    {
        echo "\n**Now testing Tests\\Browser\\Nova\\InstalledDomainsTable\\Policies\\Delete\\TestOwnersDomainOnly**";

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->assertSee('Installed Domains')
                ->clickLink('Installed Domains')
                ->pause($pause['long'])
                ->assertVisible('@1-delete-button')
                ->assertVisible('@2-delete-button')
                ->assertVisible('@3-delete-button')
            ;
        });
    }
}
