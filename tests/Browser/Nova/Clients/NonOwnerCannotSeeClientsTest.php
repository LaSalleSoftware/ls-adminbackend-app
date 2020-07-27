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
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\Clients;

// LaSalle Software
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;


class NonOwnerCannotSeeClientsTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
     * Super admins cannot see the clients Nova resource
     *
     * @group Client
     * @group ClientNonownercannotseeclient
     * @group ClientNonownercannotseeclientSuperadmincannotsee
     */
    public function testSuperAdminCannotSee()
    {
        echo "\n**Now testing Tests\Browser\Nova\Clients\NonOwnerCannotSeeClientsTest**";  

        $login = $this->loginSuperadminDomain1;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->assertDontSee('Clients')
                ->visit('nova/resources/clients')
                ->pause($pause['short'])
                ->assertDontSee('Create Client')
            ;
        });
    }

    /**
     * Admins cannot see the clients Nova resource
     *
     * @group Client
     * @group ClientNonownercannotseeclient
     * @group ClientNonownercannotseeclientAdminscannotsee
     */
    public function testAdminsCannotSee()
    {
        $login = $this->loginAdminDomain1;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->assertDontSee('Clients')
                ->visit('nova/resources/clients')
                ->pause($pause['short'])
                ->assertDontSee('Create Client')
            ;
        });
    }
}