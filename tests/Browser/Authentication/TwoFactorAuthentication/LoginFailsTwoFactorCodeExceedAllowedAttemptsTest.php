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
 * @link       https://lasallesoftware.ca Blog, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Authentication\TwoFactorAuthentication;

// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;


class LoginFailsTwoFactorCodeExceedAllowedAttemptsTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;


    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
    }

    /**
     * Test that the login fails when the number of attempts to enter the correct 2FA code exceed the allowed limit
     *
     * @group authentication--SKIP
     * @group authenticationTwofactorauthentication
     * @group authenticationTwofactorauthenticationLoginfailstwofactorcodeexceedallowedattempts
     * @group authenticationTwofactorauthenticationLoginfailstwofactorcodeexceedallowedattemptsLoginfails
     */
    public function testLoginFailsTwoFactorCodeExceedAllowedAttempts()
    {
        echo "\n**Now testing Tests\Browser\Authentication\TwoFactorAuthentication\LoginFailsTwoFactorCodeExceedAllowedAttemptsTest**";

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();
        config(['lasallesoftware-librarybackend.number_of_minutes_until_a_two_factor_code_expires' => 5]);
        config(['lasallesoftware-librarybackend.number_of_attempts_allowed_to_validate_a_two_factor_code' => 3]);

        $this->browse(function (Browser $browser) use ($login, $pause) {
            $browser->visit('/login')
                ->type('email', $login['email'])
                ->assertDontSee('Password')
                ->press('Click to Proceed')
                ->pause($pause['short'])
             ;

             $code = DB::table('twofactorauthentication')->where('email', $login['email'])->pluck('two_factor_code')->first();
             DB::table('twofactorauthentication')->where('email', $login['email'])->update(['number_of_attempts_to_validate' => 4]);
             
             $browser
                ->assertSee('Two Factor Code')
                ->type('two_factor_code', $code)
                ->press('Click to Proceed')
                ->pause($pause['short'])
                ->assertSee('Too many attempts! Please check your email to enter your fresh Two Factor Code')
             ;

             // A new two factor code is created after the exceed attempts error. So, see if a new code was actually created.
            $new_code = DB::table('twofactorauthentication')->where('email', $login['email'])->pluck('two_factor_code')->first();
            $this->assertNotEquals($code, $new_code);
        });
    }
}