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

namespace Tests\Browser\Authentication;

// LaSalle Software class
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoggingOutTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];
    }

    /**
     * Test that the correct credentials result in a successful login
     *
     * @group authentication
     * @group authenticationLogout
     */
    public function testLoginShouldBeSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Authentication\LoggingOutTest **";

        $personTryingToLogin = $this->personTryingToLogin;

        $this->browse(function (Browser $browser) use ($personTryingToLogin) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(5000)
                ->visit('/logout')
                ->click('@logout-button')
                ->pause(5000)
                ->assertSee('REGISTER')
                ->assertSee('Laravel')
            ;
        });

        // hard coding the values that are expected, made possible by my database table seeding
        $this->assertDatabaseMissing('logins', ['personbydomain_id' => 1]);
        $this->assertDatabaseMissing('logins', ['uuid' => Uuid::find(2)->uuid]);
        $this->assertDatabaseMissing('logins', ['created_by' => 1]);
    }
}
