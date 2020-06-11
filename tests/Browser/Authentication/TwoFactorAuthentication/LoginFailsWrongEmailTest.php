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


class LoginFailsWrongEmailTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;


    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
    }

    /**
     * Test that the login fails when the wrong email address is entered
     *
     * @group authentication--SKIP
     * @group authenticationTwofactorauthentication
     * @group authenticationTwofactorauthenticationTwofactorLoginfailswrongemail
     * @group authenticationTwofactorauthenticationTwofactorLoginfailswrongemailLoginfails
     */
    public function testLoginFailsWrongEmail()
    {
        echo "\n**Now testing Tests\Browser\Authentication\TwoFactorAuthentication\LoginFailsWrongEmailTest**";

        $pause = $this->pause();
        config(['lasallesoftware-librarybackend.number_of_minutes_until_a_two_factor_code_expires' => 5]);
        config(['lasallesoftware-librarybackend.number_of_attempts_allowed_to_validate_a_two_factor_code' => 3]);

        $this->browse(function (Browser $browser) use ($pause) {
            $browser->visit('/login')
                ->type('email', 'wrong@email.eh')
                ->assertDontSee('Password')
                ->press('Click to Proceed')
                ->pause($pause['short'])
                ->assertSee('The selected email is invalid.')
             ;
        });
    }
}