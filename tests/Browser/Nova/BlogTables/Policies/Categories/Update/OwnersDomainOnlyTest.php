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

namespace Tests\Browser\Nova\BlogTables\Policies\Categories\Update;


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
     * Test that the an owner can update all categories
     *
     * @group nova
     * @group novablogtables
     * @group NovaBlogtablesPolicies
     * @group NovaBlogtablesPoliciesCategories
     * @group NovaBlogtablesPoliciesCategoriesUpdate
     * @group NovaBlogtablesPoliciesCategoriesUpdateOwnersdomainonly
     */
    public function testOwnersDomainOnly()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\Policies\Categories\Update\TestOwnersDomainOnly**";

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertSee('Categories')
                ->clickLink('Categories')
                ->waitFor('@1-row')
                ->assertSee('Create Category')
                ->assertVisible('@1-edit-button')
                ->assertVisible('@2-edit-button')
                ->assertVisible('@3-edit-button')
                ->assertVisible('@4-edit-button')
                ->assertVisible('@5-edit-button')
                ->assertVisible('@6-edit-button')
                ->assertVisible('@7-edit-button')
            ;
        });
    }
}
