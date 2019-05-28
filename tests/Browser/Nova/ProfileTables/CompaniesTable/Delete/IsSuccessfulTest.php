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

namespace Tests\Browser\Nova\ProfileTables\CompaniesTable\Delete;

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
    /*
     * Dusk will pause its browser traversal by this value, in ms
     *
     * @var int
     */
    protected $pause = 1500;

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
     * Test that a deletion is successful
     *
     * @group nova
     * @group novacompany
     * @group novacompanydelete
     * @group novacompanydeleteissuccessful
     */
    public function testDeleteIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\CompaniesTable\Delete\IsSuccessfulTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $pause               = $this->pause;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Companies')
                ->waitFor('@1-row')
                ->assertVisible('@1-row')

                ->assertVisible('@1-delete-button')

                ->click('@1-delete-button')
                ->pause($pause)
                ->click('#confirm-delete-button')
                ->pause($pause)
            ;
        });

        $this->assertDatabaseMissing('companies', ['name' => 'House of Blues']);
    }
}
