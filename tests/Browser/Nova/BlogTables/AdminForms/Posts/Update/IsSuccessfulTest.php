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

namespace Tests\Browser\Nova\BlogTables\AdminForms\Posts\Update;

// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
use Lasallesoftware\Blogbackend\Models\Post;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IsSuccessfulTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the update is successful
     *
     * @group nova
     * @group novablogtables
     * @group novablogtablesadminforms
     * @group novablogtablesadminformsposts
     * @group novablogtablesadminformspostsupdate
     * @group novablogtablesadminformspostsupdateissuccessful
     */
    public function testUpdateRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\AdminForms\Posts\Update\TestUpdateRecordIsSuccessful**";

        $login        = $this->loginOwnerBobBloom;
        $pause        = $this->pause;
        $editPostData = $this->editPostData;

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $editPostData) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Posts')
                ->pause($pause['medium'])
                ->assertVisible('@1-row')
                ->assertVisible('@1-edit-button')
                ->click('@1-edit-button')
                ->pause($pause['medium'])
                ->assertSee('Update Post')
                ->type('@title', $editPostData['title'])
                ->click('@update-button')
                ->pause($pause['medium'])
                ->assertSee('Post Details')
                ->assertSee(ucwords($editPostData['title']))
            ;

            $post = Post::find(1);
            $uuid = $this->getSecondLastUuidId();

            $browser->assertPathIs('/nova/resources/posts/'.$post->id);
            $this->assertEquals($editPostData['title'], $post->title, 'title');

            $this->assertEquals($uuid->uuid, $post->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 8);
        });


    }
}