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

class RequiredTitleValidationFailsTest extends BlogTablesBaseDuskTestCase
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
     * @group novablogtablesadminforms
     * @group novablogtablesadminformscategories
     * @group novablogtablesadminformscategoriescreation
     * @group novablogtablesadminformscategoriescreationrequiredtitlevalidationfails
     */
    public function testRequiredTitleValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\AdminForms\Categories\Creation\TestRequiredTitleValidationFails**";

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
                ->typeTrix('trix-content', $newCategoryData['content'])
                ->type('@description',     $newCategoryData['description'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('The title field is required')
            ;
        });
    }
}
