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
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RequiredContentValidationFailsTest extends BlogTablesBaseDuskTestCase
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
     * @group novablogtablesadminformsposts
     * @group novablogtablesadminformspostscreation
     * @group novablogtablesadminformspostscreationrequiredcontentvalidationfails
     */
    public function testRequiredContentValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\AdminForms\Posts\Creation\TestRequiredContentValidationFails**";

        $login       = $this->loginOwnerBobBloom;
        $pause       = $this->pause;
        $newPostData = $this->newPostData;

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $newPostData) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Posts')
                ->pause($pause['shortest'])
                ->assertSee('Create Post')
                ->clickLink('Create Post')
                ->pause($pause['medium'])
                ->assertSee('Create Post')
                ->select('@installed_domain', $newPostData['installed_domain_id'])
                ->type('@title',              $newPostData['title'])
                ->type('@publish_on',         $newPostData['publish_on'])
                ->select('@category',         $newPostData['category_id'])
                ->click('@create-button')
                ->pause($pause['medium'])
                ->assertSee('The content field is required')
            ;
        });
    }
}