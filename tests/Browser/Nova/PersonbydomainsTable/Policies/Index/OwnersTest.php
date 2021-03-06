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

namespace Tests\Browser\Nova\PersonbydomainsTable\Policies\Index;


// ** WELL, THIS IS NOT ACTUALLY TESTING POLICIES, BUT THE NOVA RESOURCE SETTING. BUT THIS TEST IS STAYING IN THIS FOLDER ANYWAYS! **


// LaSalle Software
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OwnersTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the index listing displays the proper records.
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesIndex
     * @group novaPersonbydomainPoliciesIndexOwners
     */
    public function testOwners()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Policies\Index\TestOwners**";

        // Arrange
        $this->updateInstalleddomainid();

        $personTryingToLogin  = $this->loginOwnerBobBloom;
        $pause                = $this->pause();

        // Act, Assert
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->assertSee('Lookup User Roles')  // just an added assert that this menu item is visible in the sidebar
                ->clickLink('Personbydomains')
                ->pause($pause['long'])
                ->assertVisible('@1-row')
                ->assertVisible('@2-row')
                ->assertVisible('@3-row')
                ->assertVisible('@4-row')
                ->assertVisible('@5-row')
                ->assertSee('pretendfrontend.com')
                ->assertSee('anotherpretendfrontend.com')
                ->assertSee(env('LASALLE_APP_DOMAIN_NAME'))
            ;
        });
    }
}
