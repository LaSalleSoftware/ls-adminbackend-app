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
 * @link       https://lasallesoftware.ca \lookup_social_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\ProfileTables\SocialsTable\Delete;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IsSuccessfulTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $newData;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email' => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];
    }

    /**
     * Test that a deletion is successful
     *
     * @group nova
     * @group novasocial
     * @group novasocialdeleteissuccessful
     */
    public function testDeleteIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\SocialsTable\Delete\IsSuccessfulTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $newData = $this->newData;

        $this->browse(function (Browser $browser) use ($personTryingToLogin) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Social Sites')
                ->waitFor('@1-row')
                ->assertVisible('@1-row')

                ->assertMissing('@1-delete-button')
                ->assertVisible('@2-delete-button')

                ->click('@2-delete-button')
                ->pause(500)
                ->click('#confirm-delete-button')
                ->pause(500)
            ;
        });

        $this->assertDatabaseMissing('socials', ['url' => 'https://www.allaboutbluesmusic.com']);
    }
}
