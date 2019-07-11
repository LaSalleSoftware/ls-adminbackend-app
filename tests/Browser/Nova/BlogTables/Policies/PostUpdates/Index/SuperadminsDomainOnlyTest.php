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

namespace Tests\Browser\Nova\BlogTables\Policies\Postupdates\Index;


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
     * Test that the a super admin can see only postupdates that belong to their domain.
     *
     * Please note that the index listing is controlled by the resource's indexQuery() method!
     *
     * @group nova
     * @group novablogtables
     * @group NovaBlogtablesPolicies
     * @group NovaBlogtablesPoliciesPostupdates
     * @group NovaBlogtablesPoliciesPostupdatesIndex
     * @group NovaBlogtablesPoliciesPostupdatesIndexSuperadmin
     */
    public function testIndexListingListsSuperadminsDomainOnly()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\Policies\Postupdates\Index\TestSuperadminsDomainOnly**";

        $login            = $this->loginSuperadminDomain1;
        $postupdateTitles = $this->postupdateTitles;
        $pause            = $this->pause;

        $this->browse(function (LaSalleBrowser $browser) use ($login, $postupdateTitles, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertSee('Post Updates')
                ->clickLink('Post Updates')
                ->waitFor('@1-row')
                ->assertSee('Create Post Update')

                // Super admin should see all three post updates, as each belong to the super admin's domain
                ->assertSee($postupdateTitles['1'])
                ->assertSee($postupdateTitles['2'])
                ->assertSee($postupdateTitles['3'])

                ->assertVisible('@1-row')
                ->assertVisible('@2-row')
                ->assertVisible('@3-row')
            ;
        });
    }
}
