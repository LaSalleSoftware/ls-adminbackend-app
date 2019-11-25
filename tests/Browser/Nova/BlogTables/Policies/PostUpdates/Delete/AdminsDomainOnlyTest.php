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

namespace Tests\Browser\Nova\BlogTables\Policies\Postupdates\Delete;


// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminsDomainOnlyTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the a super admin can only delete postupdates that they authored
     *
     * @group nova
     * @group novablogtables
     * @group NovaBlogtablesPolicies
     * @group NovaBlogtablesPoliciesPostupdates
     * @group NovaBlogtablesPoliciesPostupdatesDelete
     * @group NovaBlogtablesPoliciesPostupdatesDeleteAdmin
     */
    public function testAdminsDomainOnly()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\Policies\Postupdates\Delete\TestAdminsDomainOnly**";

        $login = $this->loginAdminDomain1;
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
                ->assertSee('Post Updates')
                ->clickLink('Post Updates')
                ->pause($pause['long'])
                ->assertSee('Create Post Update')
                ->assertMissing('@1-delete-button')
                ->assertVisible('@2-delete-button')
                ->assertMissing('@3-delete-button')
            ;
        });
    }
}
