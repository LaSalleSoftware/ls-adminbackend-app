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

class SuperadminsDomainOnlyTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the a super admin can see only posts that belong to their domain.
     *
     * Please note that the index listing is controlled by the resource's indexQuery() method!
     *
     * @group nova
     * @group novablogtables
     * @group NovaBlogtablesPolicies
     * @group NovaBlogtablesPoliciesPosts
     * @group NovaBlogtablesPoliciesPostsIndex
     * @group NovaBlogtablesPoliciesPostsIndexSuperadminsdomainonly
     */
    public function testIndexListingListsSuperadminsDomainOnly()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\Policies\Posts\Index\TestSuperadminsDomainOnly**";

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
                ->assertSee('Posts')
                ->clickLink('Posts')
                ->pause($pause['long'])
                ->assertSee('Create Post')

                ->assertSee('Biography Of Blues Boy King On Domain 1')
                ->assertSee('Biography Of Robert Johnson On Domain 1')
                ->assertSee('Biography Of Stevie Ray Vaughan On Domain 1')
                ->assertDontSee('Biography Of Blues Boy King On Domain 2')
                ->assertDontSee('Biography Of Robert Johnson On Domain 2')
                ->assertDontSee('Biography Of Stevie Ray Vaughan On Domain 2')
                ->assertDontSee('Biography Of Blues Boy King On Domain 3')
                ->assertDontSee('Biography Of Robert Johnson On Domain 3')
                ->assertDontSee('Biography Of Stevie Ray Vaughan On Domain 3')

                ->assertVisible('@1-row')
                ->assertVisible('@2-row')
                ->assertVisible('@3-row')
                ->assertMissing('@4-row')
                ->assertMissing('@5-row')
                ->assertMissing('@6-row')
                ->assertMissing('@7-row')
                ->assertMissing('@8-row')
                ->assertMissing('@9-row')
            ;
        });
    }
}
