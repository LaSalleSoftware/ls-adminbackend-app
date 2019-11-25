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

namespace Tests\Browser\Nova\BlogTables\Policies\Posts\View;


// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OwnersDomainOnlyTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the an owner can view all posts
     *
     * @group nova
     * @group novablogtables
     * @group NovaBlogtablesPolicies
     * @group NovaBlogtablesPoliciesPosts
     * @group NovaBlogtablesPoliciesPostsView
     * @group NovaBlogtablesPoliciesPostsViewOwnersdomainonly
     */
    public function testOwnersDomainOnly()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\Policies\Posts\View\TestOwnersDomainOnly**";

        $login = $this->loginOwnerBobBloom;
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
                ->assertSee('Posts')
                ->clickLink('Posts')
                ->pause($pause['long'])
                ->assertSee('Create Post')
                ->assertVisible('@1-view-button')
                ->assertVisible('@2-view-button')
                ->assertVisible('@3-view-button')
                ->assertVisible('@4-view-button')
                ->assertVisible('@5-view-button')
                ->assertVisible('@6-view-button')
                ->assertVisible('@7-view-button')
            ;
        });
    }
}
