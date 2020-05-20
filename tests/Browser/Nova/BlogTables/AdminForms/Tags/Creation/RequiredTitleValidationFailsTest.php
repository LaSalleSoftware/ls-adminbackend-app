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

namespace Tests\Browser\Nova\BlogTables\AdminForms\Tags\Creation;

// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
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
     * @group novablogtablesadminformstags
     * @group novablogtablesadminformstagscreation
     * @group novablogtablesadminformstagscreationrequiredtitlevalidationfails
     */
    public function testRequiredTitleValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\AdminForms\Tags\Creation\TestRequiredTitleValidationFails**";

        $login      = $this->loginOwnerBobBloom;
        $pause      = $this->pause();
        $newTagData = $this->newTagData;

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $newTagData) {
            $browser->resize(1200, 900)
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->clickLink('Tags')
                ->pause($pause['long'])
                ->assertSee('Create Tag')
                ->clickLink('Create Tag')
                ->pause($pause['long'])
                ->assertSee('Create Tag')
                ->select('@installed_domain', $newTagData['installed_domain_id'])
                ->type('@description',        $newTagData['description'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('The title field is required')
            ;
        });
    }
}
