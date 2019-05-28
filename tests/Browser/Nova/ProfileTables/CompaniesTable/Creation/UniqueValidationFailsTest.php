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

namespace Tests\Browser\Nova\ProfileTables\CompaniesTable\Creation;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Company;
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UniqueValidationFailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $newNew;

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

        $this->newData = [
            'name' => 'House of Blues',
        ];
    }

    /**
     * Test that the creation fails when the primary field is not unique
     *
     * @group nova
     * @group novacompany
     * @group novacompanycreation
     * @group novacompanycreationuniqueval
     */
    public function testUniqueValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\CompaniesTable\Creation\UniqueValidationFailsTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $newData             = $this->newData;
        $pause               = $this->pause;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $newData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Companies')
                ->waitFor('@create-button')
                ->click('@create-button')
                ->pause($pause)
                ->assertSee('New Company')
                ->type('@name', $newData['name'])
                ->click('@create-button')
                ->pause($pause)
                ->assertSee('The name has already been taken')
            ;
        });
    }
}
