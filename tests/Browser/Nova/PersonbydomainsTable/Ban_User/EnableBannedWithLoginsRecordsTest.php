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
use Lasallesoftware\Librarybackend\Authentication\Models\Login;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class EnableBannedWithLoginsRecordsTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
     * Test that a personbydomain can be banned in the Nova personbydomain resource form
     * 
     *  ** no logins records are involved in this test **
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainBanuser
     * @group novaPersonbydomainBanuserEnablebannedwithloginsrecords
     * @group novaPersonbydomainBanuserEnablebannedwithloginsrecordsEnablebaninnovaanddeleteloginsrecords
     */
    public function testEnableBanInNovaAndDeleteLoginsRecords()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Ban_User\EnableBannedWithLoginsRecordsTest**";



        // Pretend that personbydomain id #2 is logged in
        $data = [
            'personbydomain_id' => 2,
            'token'             => 'token',
            'uuid'              => NULL,
            'created_by'        => 1,
        ];

        $login = new Login;        
        $login->createNewLoginsRecord($data);
        $this->assertDatabaseHas('logins', ['personbydomain_id' => $data['personbydomain_id']]);
        $this->assertDatabaseHas('logins', ['token'             => $data['token']]);



        // Owner logs in to ban personbydomain id #2
        $personTryingToLogin = $this->loginOwnerBobBloom;
        $pause               = $this->pause();
        
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomains')
                ->pause($pause['long'])
                ->assertSee('Create Personbydomain')
                ->assertVisible('@2-row')
                ->assertVisible('@2-edit-button')
                ->click('@2-edit-button')
                ->pause($pause['long'])
                ->assertSee('Update Personbydomain')
                ->check('Banned')
                ->click('@update-button')
                ->pause($pause['long'])
                ->assertSee('Personbydomain Details')
                ->visit('/logout')
                ->press('Logout')        
            ;
        });

        // Personbydomain id #2's login record was deleted by the ban
        $this->assertDatabaseMissing('logins', ['personbydomain_id' => $data['personbydomain_id']]);
        $this->assertDatabaseMissing('logins', ['token'             => $data['token']]);


        // Now personbydomain id #2 tries to login but cannot
        // Owner logs in to ban personbydomain id #2
        $personTryingToLogin = $this->loginSuperadminDomain2;
        $pause               = $this->pause();
        
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Login')  
                ->assertSee('Forgot Your Password?')                
            ;

            // Hey, let's lift the ban, and see if personbydomain id #2 can login
            DB::table('personbydomains')->where('id', 2)->update(['banned_enabled' => 0]);

            $browser
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Contact Form Submissions')  
                ->assertSee('Telephone Numbers')                
            ;
        });        
    }
}
