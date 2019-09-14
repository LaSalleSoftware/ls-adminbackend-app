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
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\PersonbydomainsTable\Policies\Index;


// ** WELL, THIS IS NOT ACTUALLY TESTING POLICIES, BUT THE NOVA RESOURCE SETTING. BUT THIS TEST IS STAYING IN THIS FOLDER ANYWAYS! **


// LaSalle Software
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OwnersTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
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
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomains')
                ->waitFor('@1-row')
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
