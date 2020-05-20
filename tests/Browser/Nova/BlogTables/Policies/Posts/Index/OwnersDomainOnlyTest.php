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

namespace Tests\Browser\Nova\BlogTables\Policies\Posts\Index;


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
     * Test that the owners see a list with all posts.
     *
     * Please note that the index listing is controlled by the resource's indexQuery() method!
     *
     * @group nova
     * @group novablogtables
     * @group NovaBlogtablesPolicies
     * @group NovaBlogtablesPoliciesPosts
     * @group NovaBlogtablesPoliciesPostsIndex
     * @group NovaBlogtablesPoliciesPostsIndexOwnersdomainonly
     */
    public function testOwnersDomainOnly()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\Policies\Posts\Index\TestOwnersDomainOnly**";

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

                ->assertSee('Biography Of Blues Boy King On Domain 1')
                ->assertSee('Biography Of Robert Johnson On Domain 1')
                ->assertSee('Biography Of Stevie Ray Vaughan On Domain 1')
                ->assertSee('Biography Of Blues Boy King On Domain 2')
                ->assertSee('Biography Of Robert Johnson On Domain 2')
                ->assertSee('Biography Of Stevie Ray Vaughan On Domain 2')
                ->assertSee('Biography Of Blues Boy King On Domain 3')
                ->assertSee('Biography Of Robert Johnson On Domain 3')
                ->assertSee('Biography Of Stevie Ray Vaughan On Domain 3')

                ->assertVisible('@1-row')
                ->assertVisible('@2-row')
                ->assertVisible('@3-row')
                ->assertVisible('@4-row')
                ->assertVisible('@5-row')
                ->assertVisible('@6-row')
                ->assertVisible('@7-row')
                ->assertVisible('@8-row')
                ->assertVisible('@9-row')
            ;
        });
    }
}
