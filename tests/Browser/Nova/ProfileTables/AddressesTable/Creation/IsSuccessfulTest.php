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
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\ProfileTables\AddressesTable\Creation;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Address;
use Tests\LaSalleDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IsSuccessfulTest extends LaSalleDuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $newData;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->newData = [
            'address_line_1'         => '22 South LaSalle Street',
            'city'                   => 'Chicago',
            'province'               => 'IL',
            'country'                => 'US',
            'postal_code'            => '60696',
            'lookup_address_type_id' => 1,
            'description'            => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim metus',
            'comments'               => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        ];
    }

    /**
     * Test that the creation is successful
     *
     * @group nova
     * @group novaprofiletables
     * @group novaaddress
     * @group novaaddresscreationissuccessful
     */
    public function testCreateNewRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\AddressesTable\Creation\IsSuccessfulTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $newData             = $this->newData;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $newData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->clickLink('Addresses')
                ->pause($pause['long'])
                ->assertVisible('@1-row')
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('Create Address')
                ->assertSelectHasOptions('@lookup_address_type', [1,2,3,4,5,6])
                ->type('@address_line_1', $newData['address_line_1'])
                ->pause($pause['long'])
                ->keys('@address_line_1', '{enter}')
                ->select('@lookup_address_type', $newData['lookup_address_type_id'])
                ->type('@description', $newData['description'])
                ->type('@comments', $newData['comments'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('Address Details')
            ;

            $address = Address::orderBy('id', 'desc')->first();
            $uuid    = $this->getSecondLastUuidId();

            $browser->assertPathIs('/nova/resources/addresses/'.$address->id);
            $this->assertEquals($newData['address_line_1'],         $address->address_line_1);
            $this->assertEquals($newData['city'],                   $address->city);
            $this->assertEquals($newData['province'],               $address->province);
            $this->assertEquals($newData['country'],                $address->country);
            $this->assertEquals($newData['postal_code'],            $address->postal_code);
            $this->assertEquals($newData['lookup_address_type_id'], $address->lookup_address_type_id);
            $this->assertEquals($newData['description'],            $address->description);
            $this->assertEquals($newData['comments'],               $address->comments);

            $this->assertEquals($uuid->uuid, $address->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 7);

        });

        // these assertions are not needed, but still getting a feel for Dusk so here they are
        $this->assertDatabaseHas('addresses', ['address_line_1' => $newData['address_line_1']]);
        $this->assertDatabaseHas('addresses', ['city'           => $newData['city']]);
        $this->assertDatabaseHas('addresses', ['province'       => $newData['province']]);
        $this->assertDatabaseHas('addresses', ['country'        => $newData['country']]);
        $this->assertDatabaseHas('addresses', ['postal_code'    => $newData['postal_code']]);
        $this->assertDatabaseHas('addresses', ['description'    => $newData['description']]);
        $this->assertDatabaseHas('addresses', ['comments'       => $newData['comments']]);
    }
}
