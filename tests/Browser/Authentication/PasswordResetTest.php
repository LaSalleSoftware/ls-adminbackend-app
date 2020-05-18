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
 * @link       https://lasallesoftware.ca Blog, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Authentication;

// LaSalle Software classes
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid;
use Lasallesoftware\Library\Authentication\Models\Login;
use Tests\LaSalleDuskTestCase;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class PasswordResetTest extends LaSalleDuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email'        => 'bob.bloom@lasallesoftware.ca',
            'password'     => 'secret',
            'password_new' => 'newsecretpassword'
        ];
    }

    /**
     * Test that the password reset sequence is successful
     *
     * @group authentication
     * @group authenticationPasswordreset
     * @group authenticationPasswordresetIssuccessful
     */
    public function testIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Authentication\PasswordResetTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $pause                = $this->pause();

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $pause) {

            // Go to Reset Password, and submit the form 
            $browser->visit('/login')
                ->assertSee('Forgot Your Password?')
                ->assertSee('Login')
                ->clickLink('Forgot Your Password?')
                ->pause($pause['short'])
                ->assertSee('Reset Password')
                ->assertSee('Send Password Reset Link')
                ->type('email', $personTryingToLogin['email'])
                ->press('Send Password Reset Link')
                ->pause($pause['long'])
                ->assertSee('We have emailed your password reset link!')                
             ;
             
             // Here is the plain text and hashed token we are going to use for this test. Update the db with this hashed token.
             $password_reset_token        = 'cc448c6de4c9757059c60eb1dafeb0ad3f5ce556ed7f092d75b57ac63b92e3e5';
             $password_reset_token_hashed = '$2y$10$GIMede2mqaBLlkpCZauiJ.M.evfL3juAzc2XKkEkLWDFC143hx8EW';
             DB::table('password_resets')->where('email', $personTryingToLogin['email'])->update(['token' => $password_reset_token_hashed]);

             // Manually construct the password/reset/{token} URL that the user would click in the password reset email they received.            
             $password_reset_path  = '/password/reset/' . $password_reset_token . '?email=' . $personTryingToLogin['email'];

             $browser->visit($password_reset_path)
                ->pause($pause['long'])
                ->assertSee('Reset Password')
                ->assertSee('Confirm Password')
                ->assertSee('Reset Password')
                ->type('password', $personTryingToLogin['password_new'])
                ->type('password_confirmation', $personTryingToLogin['password_new'])
                ->press('Reset Password')
                ->pause($pause['long'])

                // PASSWORD RESET SUCCESSFUL CAUSES REDIRECT TO THE LOGIN FORM
                ->assertSee('Forgot Your Password?')
                ->assertSee('Login')
                ->type('email',    $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password_new'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('ResourcesXXXX')
                ->assertSee('JWT Keys')
             ;
       });
    }
}
