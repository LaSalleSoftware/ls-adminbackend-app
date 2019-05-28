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
use Lasallesoftware\Library\Profiles\Models\Lookup_address_type;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IndexLookupTableTest extends DuskTestCase
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
     * Test that can see the lookup table's index view
     *
     * @group nova
     */
    public function testSeeTheLookupTableIndexView()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\IndexLookupTableTest**";

        $personTryingToLogin = $this->personTryingToLogin;

        $this->browse(function (Browser $browser) use ($personTryingToLogin) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Lookup Address Types')
                ->pause(1000)
                ->assertVisible('@1-row')
                ->assertVisible('@2-row')
                ->assertVisible('@3-row')
                ->assertVisible('@4-row')
                ->assertVisible('@5-row')
            ;
        });
    }

    /**
     * Test that the lookup table record creation fails due to the domainbyperson (aka "The User")
     * not having the owner role
     *
     * @group nova
     */
    public function testSeeTheLookupTableIndexViewExpectNoNavLinkDueToNotHavingOwnerRole()
    {
        $personTryingToLogin = [
            'email'    => 'bbking@kingofblues.com',
            'password' => 'secret',
        ];

        $this->browse(function (Browser $browser) use ($personTryingToLogin) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertDontSeeLink('Lookup Address Types')
            ;
        });
    }
}
