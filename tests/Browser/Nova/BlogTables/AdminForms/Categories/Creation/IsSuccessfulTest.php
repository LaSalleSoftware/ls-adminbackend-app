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

namespace Tests\Browser\Nova\BlogTables\AdminForms\Categories\Creation;

// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
use Lasallesoftware\Blogbackend\Models\Category;
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
     * @group novablogtablesadminformscategories
     * @group novablogtablesadminformscategoriescreation
     * @group novablogtablesadminformscategoriescreationissuccessful
     */
    public function testCreateNewRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\AdminForms\Categories\Creation\TestCreateNewRecordIsSuccessful**";

        $login           = $this->loginOwnerBobBloom;
        $pause           = $this->pause();
        $newCategoryData = $this->newCategoryData;

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $newCategoryData) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Categories')
                ->pause($pause['shortest'])
                ->assertSee('Create Category')
                ->clickLink('Create Category')
                ->pause($pause['medium'])
                ->assertSee('Create Category')
                ->select('@installed_domain', $newCategoryData['installed_domain_id'])
                ->type('@title',              $newCategoryData['title'])
                ->typeTrix('trix-content',    $newCategoryData['content'])   // FIRST TIME USING MY CUSTOM typeTRIX()
                ->type('@description',        $newCategoryData['description'])
                ->click('@create-button')
                ->pause($pause['medium'])
                ->assertSee('Category Details')
            ;

        $category = Category::orderBy('id', 'desc')->first();
        $uuid     = $this->getSecondLastUuidId();

        $browser->assertPathIs('/nova/resources/categories/'.$category->id);
        $this->assertEquals($newCategoryData['title'],                                 $category->title);
        $this->assertEquals("<div>" . $newCategoryData['content'] . "</div>", $category->content);     // content is wrapped in div tags
        $this->assertEquals($newCategoryData['description'],                           $category->description);
        $this->assertEquals($newCategoryData['enabled'],                               $category->enabled);     // defaults to "1"

        $this->assertEquals($uuid->uuid, $category->uuid);
        $this->assertEquals($uuid->lasallesoftware_event_id, 7);

        });
    }
}
