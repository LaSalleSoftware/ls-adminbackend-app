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


class LoginFailsWrongTwoFactorCodeTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;


    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
    }

    /**
     * Test that the login fails when the wrong 2FA code is entered
     *
     * @group authentication--SKIP
     * @group authenticationTwofactorauthentication
     * @group authenticationTwofactorauthenticationTwofactorLoginfailswrongtwofactorcode
     * @group authenticationTwofactorauthenticationTwofactorLoginfailswrongtwofactorcodeLoginfails
     */
    public function testLoginFailsWrongTwoFactorCode()
    {
        echo "\n**Now testing Tests\Browser\Authentication\TwoFactorAuthentication\LoginFailsWrongTwoFactorCodeTest**";

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
             $wrong_code = $code . 'wrong!';
             
             $browser
                ->assertSee('Two Factor Code')
                ->type('two_factor_code', $wrong_code)
                ->press('Click to Proceed')
                ->pause($pause['short'])
                ->assertSee('You entered an incorrect two factor code. Please try again.')
             ;

             // Double checking that the two factor code remains unchanged.
            $new_code = DB::table('twofactorauthentication')->where('email', $login['email'])->pluck('two_factor_code')->first();
            $this->assertEquals($code, $new_code);
        });
    }
}