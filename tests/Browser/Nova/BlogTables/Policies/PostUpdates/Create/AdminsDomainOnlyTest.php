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

namespace Tests\Browser\Nova\BlogTables\Policies\Postupdates\Create;


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
     * Test that the an admin can create postupdates that belong to their domain only.
     *
     * @group nova
     * @group novablogtables
     * @group NovaBlogtablesPolicies
     * @group NovaBlogtablesPoliciesPostupdates
     * @group NovaBlogtablesPoliciesPostupdatesCreate
     * @group NovaBlogtablesPoliciesPostupdatesCreateAdminsdomainonly
     */
    public function testAdminsDomainOnly()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\Policies\Postupdates\Create\TestAdminsDomainOnly**";

        $login      = $this->loginAdminDomain1;
        $postTitles = $this->postTitles;
        $pause      = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $postTitles, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->clickLink('Post Updates')
                ->pause($pause['long'])
                ->assertSee('Create Post Update')
                ->clickLink('Create Post Update')
                ->pause($pause['long'])
                ->assertSee('Create Post Update')


                // The following is required to cope with the drop-down being searchable. So we cannot use the usual
                // ->select('@posts', 2). Instead, we have to go through the literal keystrokes.
                // Thank you to https://github.com/laravel/nova-dusk-suite/blob/10e02ff765a37771ae6436c112b93f6dab1819b9/tests/Browser/Pages/HasSearchableRelations.php
                ->click('[dusk="posts-search-input"]')
                ->pause($pause['long'])
                ->type('[dusk="posts-search-input"] input', 'bio')
                ->pause($pause['long'])

                // Super admin should see the third blog posts, which is the only post belonging to the admin
                ->assertDontSee($postTitles['1'])
                ->assertDontSee($postTitles['2'])
                ->assertSee($postTitles['3'])
                ->assertDontSee($postTitles['4'])
                ->assertDontSee($postTitles['5'])
                ->assertDontSee($postTitles['6'])
                ->assertDontSee($postTitles['7'])
                ->assertDontSee($postTitles['8'])
                ->assertDontSee($postTitles['9'])
            ;
        });
    }
}
