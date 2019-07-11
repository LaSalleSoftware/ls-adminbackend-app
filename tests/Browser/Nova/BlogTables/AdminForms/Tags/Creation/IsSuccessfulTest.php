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

namespace Tests\Browser\Nova\BlogTables\AdminForms\Tags\Creation;

// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
use Lasallesoftware\Blogbackend\Models\Tag;
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
     * @group novablogtablesadminformstags
     * @group novablogtablesadminformstagscreation
     * @group novablogtablesadminformstagscreationissuccessful
     */
    public function testCreateNewRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\AdminForms\Tags\Creation\TestCreateNewRecordIsSuccessful**";

        $login      = $this->loginOwnerBobBloom;
        $pause      = $this->pause;
        $newTagData = $this->newTagData;

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $newTagData) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Tags')
                ->pause($pause['shortest'])
                ->assertSee('Create Tag')
                ->clickLink('Create Tag')
                ->pause($pause['medium'])
                ->assertSee('Create Tag')
                ->select('@installed_domain', $newTagData['installed_domain_id'])
                ->type('@title',              $newTagData['title'])
                ->type('@description',        $newTagData['description'])
                ->click('@create-button')
                ->pause($pause['medium'])
                ->assertSee('Tag Details')
            ;

        $tag  = Tag::orderBy('id', 'desc')->first();
        $uuid = $this->getSecondLastUuidId();

        $browser->assertPathIs('/nova/resources/tags/'.$tag->id);
        $this->assertEquals($newTagData['title'],       $tag->title);
        $this->assertEquals($newTagData['description'], $tag->description);
        $this->assertEquals($newTagData['enabled'],     $tag->enabled);     // defaults to "1"

        $this->assertEquals($uuid->uuid, $tag->uuid);
        $this->assertEquals($uuid->lasallesoftware_event_id, 7);

        });
    }
}
