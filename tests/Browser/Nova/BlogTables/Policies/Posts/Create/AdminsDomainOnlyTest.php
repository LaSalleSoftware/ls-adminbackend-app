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

namespace Tests\Browser\Nova\BlogTables\Policies\Posts\Create;


// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminsDomainOnlyTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the an admin can create posts.
     *
     * @group nova
     * @group novablogtables
     * @group NovaBlogtablesPolicies
     * @group NovaBlogtablesPoliciesPosts
     * @group NovaBlogtablesPoliciesPostsCreate
     * @group NovaBlogtablesPoliciesPostsCreateAdminsdomainonly
     */
    public function testAdminsDomainOnly()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\Policies\Posts\Create\TestAdminsDomainOnly**";

        $login = $this->loginAdminDomain1;
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
                ->assertSee('Posts')
                ->clickLink('Posts')
                ->pause($pause['long'])
                ->assertSee('Create Post')
                ->clickLink('Create Post')
                ->pause($pause['long'])
                ->assertSee('Create Post')
                ->assertSelectHasOption('@installed_domain', 1)
                ->assertSelectMissingOptions('@installed_domain', [2,3])
            ;
        });
    }
}
