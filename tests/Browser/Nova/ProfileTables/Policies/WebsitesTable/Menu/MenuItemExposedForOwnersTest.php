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
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\ProfileTables\Policies\WebsitesTable\Menu;


// ** WELL, THIS IS NOT ACTUALLY TESTING POLICIES, BUT THE NOVA RESOURCE SETTING. BUT THIS TEST IS STAYING IN THIS FOLDER ANYWAYS! **


// LaSalle Software
use Tests\Browser\Nova\ProfileTables\ProfileTablesBaseDuskTestCase;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MenuItemExposedForOwnersTest extends ProfileTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the creation is successful
     *
     * @group nova
     * @group novaprofiletables
     * @group novaprofiletablesPolicies
     * @group novaprofiletablesPoliciesWebsites
     * @group novaprofiletablesPoliciesWebsitesMenu
     * @group novaprofiletablesPoliciesWebsitesMenuitemexposedforowners
     */
    public function testMenuItemExposedForOwnersIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\Policies\Websites\Menu\TestMenuItemExposedForOwnersIsSuccessful**";

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->assertSee('Websites')
            ;
        });
    }
}
