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

namespace Tests\Browser\Nova\PersonbydomainsTable\Ban_User;

// LaSalle Software classes
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class EmergencyBanEnabledNeedSpecialEnvFileForThisTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
     * Test that a personbydomain cannot login when the emergency ban is enabled
     *
     * @group nova
     * @group novaPersonbydomain-SKIP
     * @group novaPersonbydomainBanuser-SKIP
     * @group novaPersonbydomainBanuserEmergencyban-SKIP
     * @group novaPersonbydomainBanuserEmergencybanIsenabled
     */

     /*****************************************************************************************************************
      **   To set the env var to true -- "LASALLE_EMERGENCY_BAN_ALL_USERS_FROM_ADMIN_APP_LOGIN=true",                **
      **   you must run this test with a separate environment file:                                                  **
      **                                                                                                             **
      **   "php artisan dusk --group novaPersonbydomainBanuserEmergencybanIsenabled --env=dusk.emergencybanuser"     **
      *****************************************************************************************************************/

    public function testIsEnabled()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Ban_User\EmergencyBanEnabledNeedSpecialEnvFileForThisTest**";


        echo "\n\n";
        echo "\n *****************************************************************************************************************************************";
        echo "\n * PLEASE NOTE THAT IF THIS TEST FAILS, THEN PLEASE ENSURE THAT YOU ARE RUNNING IT WITH THE .env.dusk.emergencybanuser env file.         *";
        echo "\n *                                                                                                                                       *";
        echo "\n * The full artisan command is:                                                                                                          *";
        echo "\n *                                                                                                                                       *";
        echo "\n * php artisan dusk --group novaPersonbydomainBanuserEmergencybanIsenabled --env=dusk.emergencybanuser                                   *";
        echo "\n *                                                                                                                                       *";
        echo "\n *****************************************************************************************************************************************";
        echo "\n\n";


        $personTryingToLogin = $this->loginOwnerBobBloom;
        $pause               = $this->pause();
        
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Login')  
                ->assertSee('Forgot Your Password?') 
            ;
        });
    }
}
