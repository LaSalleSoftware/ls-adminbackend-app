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

namespace Tests\Browser\Nova\ProfileTables\PersonsTable\Details;

// LaSalle Software classes
use Tests\Browser\LaSalleDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IsSuccessfulTest extends LaSalleDuskTestCase
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
     * Test that the details view is successful
     *
     * @group nova
     * @group novaprofiletables
     * @group novaperson
     * @group novapersondetailsissuccessful
     */
    public function testDetailsViewIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\PersonsTable\Details\IsSuccessfulTest**";

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
                ->clickLink('People')
                ->waitFor('@create-button')
                ->assertVisible('@create-button')
                ->type('@search', 'Blues Boy')
                ->waitFor('@3-row')
                ->assertVisible('@3-row')
                ->assertVisible('@3-view-button')
                ->click('@3-view-button')
                ->pause($pause['long'])
                ->assertSee('Person Details')
                ->assertPathIs('/nova/resources/people/3')
                ->assertSee('Blues Boy')
                ->assertSee('bbking@kingofblues.com')
                ->assertSee('https://www.allaboutbluesmusic.com/delta-blues/')
                ->assertSee('1 (555) 123-4567')
                ->assertSee('https://www.mlb.com/bluejays')
            ;
        });
    }
}
