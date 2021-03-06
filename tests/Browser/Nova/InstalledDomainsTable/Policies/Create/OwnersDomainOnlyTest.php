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

namespace Tests\Browser\Nova\InstalledDomainsTable\Policies\Create;

// LaSalle Software
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;
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

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lslibrarybackend:installeddomainseed');
    }

    /**
     * Test that the an owner cannot see the "Create Installed Domain" button.
     *
     * @group nova
     * @group NovaInstalleddomainstable
     * @group NovaInstalleddomainstablePolicies
     * @group NovaInstalleddomainstablePoliciesCreate
     * @group NovaInstalleddomainstablePoliciesCreateOwners
     * @group NovaInstalleddomainstablePoliciesCreateOwnersSeecreateinstalleddomainbutton
     */
    public function testSeeCreateInstallDomainButton()
    {
        echo "\n**Now testing Tests\\Browser\\Nova\\InstalledDomainsTable\\Policies\\Create\\TestOwnersDomainOnly**";

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
                ->assertSee('Create Installed Domain')
            ;
        });
    }

    /**
     * Test that the an owner cannot get to the create form directly.
     *
     * @group nova
     * @group NovaInstalleddomainstable
     * @group NovaInstalleddomainstablePolicies
     * @group NovaInstalleddomainstablePoliciesCreate
     * @group NovaInstalleddomainstablePoliciesCreateOwners
     * @group NovaInstalleddomainstablePoliciesCreateOwnersCanaccesscreateformdirectly
     */
    public function testCanAccessCreateFormDirectly()
    {
        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs(config('lasallesoftware-librarybackend.web_middleware_default_path'))
                ->assertSee('Personbydomains')
                ->assertSee('Installed Domains')
                ->visit('nova/resources/installed_domains/new?viaResource=&viaResourceId=&viaRelationship')
                ->pause($pause['long'])
                ->assertSee('Create Installed Domain')
            ;
        });
    }
}
