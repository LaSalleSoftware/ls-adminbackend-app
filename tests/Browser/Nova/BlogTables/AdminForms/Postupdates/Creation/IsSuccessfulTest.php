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

namespace Tests\Browser\Nova\BlogTables\AdminForms\Postupdates\Creation;

// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
use Lasallesoftware\Blogbackend\Models\Postupdate;
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
     * Test that the creation is successful
     *
     * @group nova
     * @group novablogtables
     * @group novablogtablesadminforms
     * @group novablogtablesadminformspostupdates
     * @group novablogtablesadminformspostupdatescreation
     * @group novablogtablesadminformspostupdatescreationsissuccessful
     */
    public function testCreateNewRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\AdminForms\Postupdates\Creation\TestCreateNewRecordIsSuccessful**";

        $login             = $this->loginOwnerBobBloom;
        $pause             = $this->pause();
        $newPostupdateData = $this->newPostupdateData;

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $newPostupdateData) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Post Updates')
                ->pause($pause['shortest'])
                ->assertSee('Create Post Update')
                ->clickLink('Create Post Update')
                ->pause($pause['shortest'])
                ->assertSee('Create Post Update')


                // The following is required to cope with the drop-down being searchable. So we cannot use the usual
                // ->select('@posts', 2). Instead, we have to go through the literal keystrokes.
                // Thank you to https://github.com/laravel/nova-dusk-suite/blob/10e02ff765a37771ae6436c112b93f6dab1819b9/tests/Browser/Pages/HasSearchableRelations.php
                ->click('[dusk="posts-search-input"]')
                ->pause(100)
                ->type('[dusk="posts-search-input"] input', $newPostupdateData['post_title'])
                ->pause($pause['medium'])
                ->keys('[dusk="posts-search-input"] input', ['{enter}'])


                // continue!
                ->type('@title',           $newPostupdateData['title'])
                ->typeTrix('trix-content', $newPostupdateData['content'])
                ->click('@create-button')
                ->pause($pause['medium'])
                ->assertSee('Post Update Details')
            ;

        $postupdate = Postupdate::orderBy('id', 'desc')->first();
        $uuid       = $this->getSecondLastUuidId();

        $browser->assertPathIs('/nova/resources/postupdates/'.$postupdate->id);
        $this->assertEquals($newPostupdateData['installed_domain_id'],                   $postupdate->installed_domain_id, 'installed_domain_id');
        $this->assertEquals($newPostupdateData['personbydomain_id'],                     $postupdate->personbydomain_id, 'personbydomain_id');
        $this->assertEquals($newPostupdateData['post_id'],                               $postupdate->post_id, 'post_id');
        $this->assertEquals($newPostupdateData['title'],                                 $postupdate->title, 'title');
        $this->assertEquals("<div>" . $newPostupdateData['content'] . "</div>", $postupdate->content, 'content');     // content is wrapped in div tags
        $this->assertEquals($newPostupdateData['excerpt'],                               $postupdate->excerpt, 'excerpt');
        $this->assertEquals($newPostupdateData['enabled'],                               $postupdate->enabled, 'enabled');     // defaults to "1"
        $this->assertEquals($uuid->uuid, $postupdate->uuid);
        $this->assertEquals($uuid->lasallesoftware_event_id, 7);
        });
    }
}
