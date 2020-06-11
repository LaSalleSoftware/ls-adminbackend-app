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


class LoginFailsWrongPasswordTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;


    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
    }

    /**
     * Test that the login fails when wrong password is entered
     *
     * @group authentication--SKIP
     * @group authenticationTwofactorauthentication
     * @group authenticationTwofactorauthenticationTwofactorLoginfailswrongpassword
     * @group authenticationTwofactorauthenticationTwofactorLoginfailswrongpasswordLoginfails
     */
    public function testLoginFailsWrongPassword()
    {
        echo "\n**Now testing Tests\Browser\Authentication\TwoFactorAuthentication\LoginFailsWrongPasswordTest**";

        $login = $this->loginOwnerBobBloom;
        $wrong_password = $login['password'] . 'wrong!';
        $pause = $this->pause();

        $this->browse(function (Browser $browser) use ($login, $pause, $wrong_password) {
            $browser->visit('/login')
                ->type('email', $login['email'])
                ->assertDontSee('Password')
                ->press('Click to Proceed')
                ->pause($pause['short'])
             ;

             $code = DB::table('twofactorauthentication')->where('email', $login['email'])->pluck('two_factor_code')->first();
             
             $browser
                ->assertSee('Two Factor Code')
                ->type('two_factor_code', $code)
                ->press('Click to Proceed')
                ->pause($pause['short'])
                ->type('password', $wrong_password)
                ->press('Login')
                ->pause($pause['short'])
                ->assertSee('You entered an incorrect password. Please try again.')
             ;
        });
    }
}