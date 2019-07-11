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

namespace Tests\Browser\Nova\LookupTables;

// LaSalle Software classes
use Lasallesoftware\Library\Dusk\LaSalleBrowser;
use Tests\Browser\LaSalleDuskTestCase;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DetailsLookupTableTest extends LaSalleDuskTestCase
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
     * Test that can see the lookup table's details view
     *
     * @group nova
     * @group novalookuptables
     */
    public function testSeeTheLookupTableDetailsView()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\DetailsLookupTableTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $pause               = $this->pause;

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->visit('/nova/resources/lookup_address_types/5')
                ->pause($pause['medium'])
                ->assertSee('Work')
            ;
        });
    }

    /**
     * Test that the lookup table record update fails due to the domainbyperson (aka "The User")
     * not having the owner role
     *
     * @group nova
     * @group novalookuptables
     */
    public function testSeeTheLookupTableDetailsViewExpectNoViewDueToNotHavingOwnerRole()
    {
        $personTryingToLogin = [
            'email'    => 'bbking@kingofblues.com',
            'password' => 'secret',
        ];
        $pause               = $this->pause;

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertDontSeeLink('Lookup Address Types')
                ->visit('/nova/resources/lookup_address_types/6')
                ->pause($pause['medium'])
                ->assertDontSee('Lookup Address Type Details')
            ;
        });
    }
}
