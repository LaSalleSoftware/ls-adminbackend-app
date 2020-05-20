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

namespace Tests\Browser\Nova\BlogTables\Policies\PostUpdates\CreateSuppressed;


// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class SuppressCreateDueToNoPostsOwnerTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the an owner cannot see the "Create Post Update" button because there are no applicable
     * posts with which to update.
     *
     * @group nova
     * @group novablogtables
     * @group NovaBlogtablesPolicies
     * @group NovaBlogtablesPoliciesPostupdates
     * @group NovaBlogtablesPoliciesPostupdatesCreatesuppressed
     * @group NovaBlogtablesPoliciesPostupdatesCreatesuppressedOwner
     */
    public function testSuppressCreateDueToNoPostsOwner()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\Policies\Postupdates\CreateSuppressed\TestSuppressCreateDueToNoPostsOwner**";

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        // Delete all the records in the posts db table
        DB::table('posts')->delete();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')

                // There should be no "Post Updates" menu item
                ->assertDontSee('Post Updates')

                // Well, let's go to the post updates listing anyways via direct url
                ->visit('/nova/resources/postupdates')
                ->pause($pause['long'])
                ->assertSee('Post Updates')

                // The "Create Post Update" button should be missing
                ->assertMissing('Create Post Update')
            ;
        });
    }
}
