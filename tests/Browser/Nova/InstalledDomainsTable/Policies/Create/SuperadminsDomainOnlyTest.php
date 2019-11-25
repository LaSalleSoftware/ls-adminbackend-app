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
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\InstalledDomainsTable\Policies\Create;


// LaSalle Software
use Tests\Browser\Nova\InstalledDomainsTable\InstalledDomainsTableBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SuperadminsDomainOnlyTest extends InstalledDomainsTableBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lslibrary:installeddomainseed');

    }

    /**
     * Test that the a superadmin cannot see the "Create Installed Domain" button.
     *
     * The only way that a superadmin can potentially see the "Create Installed Domain" button is by going to the
     * index listing directly.
     *
     * @group nova
     * @group NovaInstalleddomainstable
     * @group NovaInstalleddomainstablePolicies
     * @group NovaInstalleddomainstablePoliciesCreate
     * @group NovaInstalleddomainstablePoliciesCreateSuperadmins
     * @group NovaInstalleddomainstablePoliciesCreateSuperadminsDonotseecreateinstalleddomainbutton
     */
    public function testDoNotSeeCreateInstallDomainButton()
    {
        echo "\n**Now testing Tests\Browser\Nova\InstalledDomainsTable\Policies\Create\TestSuperadminsDomainOnly**";

        $login = $this->loginSuperadminDomain1;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->assertDontSee('Installed Domains')
                ->visit('nova/resources/installed_domains')
                ->pause($pause['long'])
                ->assertDontSee('Create Installed Domain')
            ;
        });
    }

    /**
     * Test that the a superadmin cannot get to the create form directly.
     *
     * @group nova
     * @group NovaInstalleddomainstable
     * @group NovaInstalleddomainstablePolicies
     * @group NovaInstalleddomainstablePoliciesCreate
     * @group NovaInstalleddomainstablePoliciesCreateSuperadmins
     * @group NovaInstalleddomainstablePoliciesCreateSuperadminsNotaccesscreateformdirectly
     */
    public function testCannotAccessCreateFormDirectly()
    {
        $login = $this->loginSuperadminDomain1;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->assertDontSee('Installed Domains')
                ->visit('nova/resources/installed_domains/new?viaResource=&viaResourceId=&viaRelationship')
                ->pause($pause['long'])
                ->assertSee('403')
            ;
        });
    }
}
