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

namespace Tests\Browser\Nova\BlogTables\AdminForms\Posts\Creation;

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
     * Test that the creation is successful.
     *
     * Note that the "publish_on" field is left blank intentionally as it will be populated via model event when left blank
     * 
     * NOTE THAT THE CATEGORY_ID IS NULL BECAUSE YOU CANNOT ASSIGN A CATEGORY ON A CREATE!
     * NOTE THAT CATEGORY_ID IS NULLABLE!
     *
     * @group nova
     * @group novablogtables
     * @group novablogtablesadminforms
     * @group novablogtablesadminformsposts
     * @group novablogtablesadminformspostscreation
     * @group novablogtablesadminformspostscreationissuccessful
     */
    public function testCreateNewRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\AdminForms\Posts\Creation\TestCreateNewRecordIsSuccessful**";

        $this->assertTrue(true);

        $login       = $this->loginOwnerBobBloom;
        $pause       = $this->pause();
        $newPostData = $this->newPostData;

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $newPostData) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->clickLink('Posts')
                ->pause($pause['long'])
                ->assertSee('Create Post')
                ->clickLink('Create Post')
                ->pause($pause['long'])
                ->assertSee('Create Post')
                ->select('@installed_domain', $newPostData['installed_domain_id'])
                ->type('@title',              $newPostData['title'])
                ->typeTrix('trix-content',    $newPostData['content'])
                ->select('@category',         $newPostData['category_id'])
                ->pause($pause['long'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('Post Details')
            ;

        $post = Post::orderBy('id', 'desc')->first();
        $uuid = $this->getSecondLastUuidId();

        $browser->assertPathIs('/nova/resources/posts/'.$post->id);
        $this->assertEquals($newPostData['installed_domain_id'],                   $post->installed_domain_id, 'installed_domain_id');
        $this->assertEquals($newPostData['personbydomain_id'],                     $post->personbydomain_id, 'personbydomain_id');
        $this->assertEquals($newPostData['category_id'],                           $post->category_id, 'category_id');
        $this->assertEquals($newPostData['title'],                                 $post->title, 'title');
        $this->assertEquals($newPostData['slug'],                                  $post->slug, 'slug');
        $this->assertEquals("<div>" . $newPostData['content'] . "</div>", $post->content, 'content');     // content is wrapped in div tags
        $this->assertEquals($newPostData['excerpt'],                               $post->excerpt, 'excerpt');
        $this->assertEquals($newPostData['meta_description'],                      $post->meta_description, 'meta_description');
        $this->assertEquals($newPostData['featured_image'],                        $post->featured_image, 'featured_image');
        $this->assertEquals($newPostData['enabled'],                               $post->enabled, 'enabled');     // defaults to "1"
        $this->assertEquals($uuid->uuid, $post->uuid);
        $this->assertEquals($uuid->lasallesoftware_event_id, 7);

        });
    }
}
