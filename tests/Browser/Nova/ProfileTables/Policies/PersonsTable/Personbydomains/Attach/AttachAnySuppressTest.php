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
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\ProfileTables\Policies\PersonsTable\Personbydomains\Attach;


// LaSalle Software
use Tests\Browser\Nova\ProfileTables\ProfileTablesBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AttachAnySuppressTest extends ProfileTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Suppress the attach button.
     *
     * @group nova
     * @group novalookuptables
     * @group novaLookuptablesPolicies
     * @group novaprofiletablesPoliciesPersonsPersonbydomains
     * @group novaprofiletablesPoliciesPersonsPersonbydomainsAttach
     * @group novaprofiletablesPoliciesPersonsPersonbydomainsAttachAttachanysuppress
     */
    public function testAttachAnySuppress()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\Policies\PersonsTable\Personbydomains\Attach\TestAttachAnySuppress**";

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertSee('People')

                ->clickLink('People')
                ->waitFor('@306-row')
                ->assertSee('Create Person')
                ->assertVisible('@306-view-button')

                ->click('@306-view-button')
                ->pause($pause['long'])
                ->assertSee('Person Details')
                ->assertSee('Personbydomain')
                ->assertVisible('@5-row')
                //->assertMissing('@attach-button') // lots of buttons with this dusk class!
                ->assertDontSee('Attach Personbydomain')
            ;
        });
    }
}
