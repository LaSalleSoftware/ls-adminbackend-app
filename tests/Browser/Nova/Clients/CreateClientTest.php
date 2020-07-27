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
use Lasallesoftware\Librarybackend\Profiles\Models\Client;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;


class CreateClientsTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
     * Create a client
     *
     * @group Client
     * @group ClientCreateclient
     * @group ClientCreateclientCreateclient
     */
    public function testCreateClient()
    {
        echo "\n**Now testing Tests\Browser\Nova\Clients\CreateClientsTest**";  

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        $data = [
            'company_id'          => 1,
            'name'                => 'The Client Name',
            'comments'            => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        ];

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $data) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Clients')
                ->clickLink('Clients')
                ->pause($pause['short'])
                ->assertSee('Create Client')
                ->clickLink('Create Client')
                ->pause($pause['short'])
                ->select('@company',          $data['company_id'])
                ->type('@name',               $data['name'])
                ->type('@comments',           $data['comments'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('Client Details')
            ;
        });

        $this->assertDatabaseHas('clients', ['company_id'        => $data['company_id']]);
        $this->assertDatabaseHas('clients', ['name'              => $data['name']]);
        $this->assertDatabaseHas('clients', ['comments'          => $data['comments']]);


        $client = Client::find(1);
        $this->assertEquals($data['company_id'],        $client->company_id, 'company_id');
        $this->assertEquals($data['name'],              $client->name, 'name');
        $this->assertEquals($data['comments'],          $client->comments, 'comments');


        $uuid = $this->getSecondLastUuidId();
        $this->assertEquals($uuid->uuid, $client->uuid);
        $this->assertEquals($uuid->lasallesoftware_event_id, 7);
    }    
}