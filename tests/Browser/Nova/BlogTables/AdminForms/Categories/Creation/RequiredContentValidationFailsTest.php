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

namespace Tests\Browser\Nova\BlogTables\AdminForms\Categories\Creation;

// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
use Lasallesoftware\Blogbackend\Models\Category;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid;

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
     * @group novablogtablesadminformscategories
     * @group novablogtablesadminformscategoriescreation
     * @group novablogtablesadminformscategoriescreationrequiredcontentvalidationfails
     */
    public function testRequiredContentValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\AdminForms\Categories\Creation\TestRequiredContentValidationFails**";

        $login           = $this->loginOwnerBobBloom;
        $pause           = $this->pause();
        $newCategoryData = $this->newCategoryData;

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $newCategoryData) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->clickLink('Categories')
                ->pause($pause['long'])
                ->assertSee('Create Category')
                ->clickLink('Create Category')
                ->pause($pause['long'])
                ->assertSee('Create Category')
                ->select('@installed_domain', $newCategoryData['installed_domain_id'])
                ->type('@title',       $newCategoryData['title'])
                ->type('@description', $newCategoryData['description'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('The content field is required')
            ;
        });
    }
}
