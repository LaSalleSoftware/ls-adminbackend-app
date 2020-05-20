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

namespace Tests\Browser\Nova\BlogTables\Policies\PostUpdates\Create;


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
     * Test that the an owner can create postupdates.
     *
     * Hey, guess what! ->assertSelectHasOptions('@post', [1,2,3,4,5,6,7,8,9]) does not work  :-(
     * Why? Because the post drop-down is searchable.
     *
     * It turns out, more as a fluke than a brilliant testing strategy, that all nine test posts have titles starting
     * with "Biography". So, if you type in "bio" in the search box, a select list with all nine titles displays. One
     * this list renders, I can do an ->assertSee() on them. How is that for convenience!
     *
     * So based on this behaviour, I can -- and do --  mimick ->assertSelectHasOptions('@post', [1,2,3,4,5,6,7,8,9]) in one go.
     *
     *
     *
     * @group nova
     * @group novablogtables
     * @group NovaBlogtablesPolicies
     * @group NovaBlogtablesPoliciesPostupdates
     * @group NovaBlogtablesPoliciesPostupdatesCreate
     * @group NovaBlogtablesPoliciesPostupdatesCreateOwner
     */
    public function testOwnersDomainOnly()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\Policies\Postupdates\Create\TestOwnersDomainOnly**";

        $login      = $this->loginOwnerBobBloom;
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

                // Owner should see all nine blog posts
                ->assertSee($postTitles['1'])
                ->assertSee($postTitles['2'])
                ->assertSee($postTitles['3'])
                ->assertSee($postTitles['4'])
                ->assertSee($postTitles['5'])
                ->assertSee($postTitles['6'])
                ->assertSee($postTitles['7'])
                ->assertSee($postTitles['8'])
                ->assertSee($postTitles['9'])
            ;
        });
    }
}
