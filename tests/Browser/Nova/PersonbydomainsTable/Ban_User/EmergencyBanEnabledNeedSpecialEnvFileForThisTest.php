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

namespace Tests\Browser\Nova\PersonbydomainsTable\Ban_User;

// LaSalle Software classes
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Authentication\Models\Personbydomain;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

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

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
     * Test that a personbydomain cannot login when the emergency ban is enabled
     *
     * @group nova
     * @group novaPersonbydomain-SKIP
     * @group novaPersonbydomainBanuser-SKIP
     * @group novaPersonbydomainBanuserEmergencyban-SKIP
     * @group novaPersonbydomainBanuserEmergencybanIsenabled-SKIP
     */


     /* 
     ================================================================================================================
     WELL, AFTER WRESTLING WITH THIS TEST, I GIVE UP:
      
       ** .env.dusk.emergencybanuser IS NOT ALWAYS USED
       ** I'm not even sure that it's using my .env.dusk.local when the "emergencybanuser" env file is ignore
       ** This test is somehow, incredibly, magically, deleting my "persons" pivot tables! IN MY "local" DATABASE!
    
     SO I'M SKIPPING THIS ONE. SEE https://lasallesoftware.ca/docs/v2/gettingstarted_tests#problem_with_ban_user_test
     ================================================================================================================
     */


     /*****************************************************************************************************************
      **                                     MUST RUN WITH THE COMMAND                                               **
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
        echo "\n *  FYI: the LASALLE_EMERGENCY_BAN_ALL_USERS_FROM_ADMIN_APP_LOGIN = " . env('LASALLE_EMERGENCY_BAN_ALL_USERS_FROM_ADMIN_APP_LOGIN') . " for this Dusk test.                                                *";
        echo "\n *                                                                                                                                       *";
        echo "\n *****************************************************************************************************************************************";

        echo "\n\n";

        echo "\n *****************************************************************************************************************************************";
        echo "\n *                                                                                                                                       *";
        echo "\n *  Unfortunately, sometimes the '.env.dusk.emergencybanuser' environment file is not read during the Dusk test.                         *";
        echo "\n *  More unfortunate is that I have not figured out the reason!                                                                          *";
        echo "\n *                                                                                                                                       *";
        echo "\n *  FYI: the LASALLE_EMERGENCY_BAN_ALL_USERS_FROM_ADMIN_APP_LOGIN = " . env('LASALLE_EMERGENCY_BAN_ALL_USERS_FROM_ADMIN_APP_LOGIN') . " for this Dusk test.                                                *";

        echo "\n *                                                                                                                                       *";
        echo "\n *****************************************************************************************************************************************";


        /*
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
        */
    }
}
