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
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\InstalledDomainsTable\Policies\View;


// LaSalle Software
use Tests\Browser\Nova\InstalledDomainsTable\InstalledDomainsTableBaseDuskTestCase;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SuperadminsDomainOnlyTest extends InstalledDomainsTableBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lslibrarybackend:installeddomainseed');

    }

    /**
     * Test that the a super admin cannot access a detail view by direct URL.
     *
     * @group nova
     * @group NovaInstalleddomainstable
     * @group NovaInstalleddomainstablePolicies
     * @group NovaInstalleddomainstablePoliciesView
     * @group NovaInstalleddomainstablePoliciesViewSuperadminsdomainonly
     */
    public function testIndexListingListsSuperadminsDomainOnly()
    {
        echo "\n**Now testing Tests\Browser\Nova\InstalledDomainsTable\Policies\View\TestSuperadminsDomainOnly**";

        $login = $this->loginSuperadminDomain1;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->visit('nova/resources/installed_domains/1')
                ->pause($pause['long'])
                ->assertSee('403')
            ;
        });
    }
}
