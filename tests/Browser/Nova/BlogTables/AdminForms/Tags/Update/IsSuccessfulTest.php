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

namespace Tests\Browser\Nova\BlogTables\AdminForms\Tags\Update;

// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
use Lasallesoftware\Blogbackend\Models\Tag;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IsSuccessfulTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the update is successful
     *
     * @group nova
     * @group novablogtables
     * @group novablogtablesadminforms
     * @group novablogtablesadminformstags
     * @group novablogtablesadminformstagsupdate
     * @group novablogtablesadminformstagsupdatesissuccessful
     */
    public function testUpdateRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\AdminForms\Tags\Update\TestUpdateRecordIsSuccessful**";

        $login       = $this->loginOwnerBobBloom;
        $pause       = $this->pause();
        $editTagData = $this->editTagData;

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $editTagData) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs(config('lasallesoftware-librarybackend.web_middleware_default_path'))
                ->assertSee('Personbydomains')
                ->clickLink('Tags')
                ->pause($pause['long'])
                ->assertVisible('@1-row')
                ->assertVisible('@1-edit-button')
                ->click('@1-edit-button')
                ->pause($pause['long'])
                ->assertSee('Update Tag')
                ->type('@title', $editTagData['title'])
                ->click('@update-button')
                ->pause($pause['long'])
                ->assertSee('Tag Details')
                ->assertSee($editTagData['title'])
            ;

            $tag  = Tag::find(1)->first();
            $uuid = $this->getSecondLastUuidId();

            $browser->assertPathIs('/nova/resources/tags/1');
            $this->assertEquals($editTagData['title'], $tag->title);

            $this->assertEquals($uuid->uuid, $tag->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 8);
        });


    }
}
