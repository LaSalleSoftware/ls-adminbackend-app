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

namespace Tests\Browser\Nova\ProfileTables\AddressesTable\Update;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Address;
use Tests\Browser\LaSalleDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;


class IsSuccessfulTest extends LaSalleDuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $updatedAddressTableData;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->updatedData = [
            'lookup_address_type_id'  => 4,
            'address_line_1'          => '1 Bills Drive',
            'address_line_2'          => null,
            'address_line_3'          => null,
            'address_line_4'          => null,
            'city'                    => 'Orchard Park',
            'province'                => 'NY',
            'postal_code'             => '14127',
            'description'             => 'Id diam vel quam elementum pulvinar etiam.',
            'comments'                => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas',
        ];
    }

    /**
     * Test that an update is successful.
     *
     * @group nova
     * @group novaprofiletables
     * @group novaaddress
     * @group novaaddresseditsuccessful
     */
    public function testEditExistingRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\AddressesTable\Update\IsSuccessfulTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $updatedData         = $this->updatedData;
        $pause               = $this->pause;

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $updatedData, $pause) {

            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Addresses')
                ->waitFor('@1-row')
                ->assertVisible('@1-row')
                ->assertSee('Create Address')
                ->assertVisible('@1-edit-button')
                ->click('@1-edit-button')
                ->waitFor('@update-button')
                ->assertVisible('@update-button')
                ->assertSee('Update Address')
                ->type('@address_line_1', $updatedData['address_line_1'])
                ->type('@address_line_2', $updatedData['address_line_2'])
                ->type('@address_line_3', $updatedData['address_line_3'])
                ->type('@address_line_4', $updatedData['address_line_4'])
                ->type('@city',           $updatedData['city'])
                ->type('@province',       $updatedData['province'])
                ->type('@postal_code',    $updatedData['postal_code'])
                ->type('@description',    $updatedData['description'])
                ->type('@comments',       $updatedData['comments'])
                ->select('@lookup_address_type', $updatedData['lookup_address_type_id'])
                ->click('@update-button')
                ->pause($pause['short'])
                ->assertSee('Address Details')
            ;

            $address = Address::orderBy('id', 'desc')->first();

            //$uuid   =   Uuid::orderby('id', 'desc')->first();
            $uuid = $this->getSecondLastUuidId();

            $browser->assertPathIs('/nova/resources/addresses/'.$address->id);
            $this->assertEquals($updatedData['address_line_1'],         $address->address_line_1);
            $this->assertEquals($updatedData['address_line_2'],         $address->address_line_2);
            $this->assertEquals($updatedData['address_line_3'],         $address->address_line_3);
            $this->assertEquals($updatedData['address_line_4'],         $address->address_line_4);
            $this->assertEquals($updatedData['city'],                   $address->city);
            $this->assertEquals($updatedData['province'],               $address->province);
            $this->assertEquals($updatedData['postal_code'],            $address->postal_code);
            $this->assertEquals($updatedData['lookup_address_type_id'], $address->lookup_address_type_id);
            $this->assertEquals($updatedData['description'],            $address->description);
            $this->assertEquals($updatedData['comments'],               $address->comments);

            $this->assertEquals($uuid->uuid, $address->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 8);
        });

        // these assertions are not needed, but still getting a feel for Dusk so here they are
        $this->assertDatabaseHas('addresses', ['address_line_1' => $updatedData['address_line_1']]);
        $this->assertDatabaseHas('addresses', ['address_line_2' => $updatedData['address_line_2']]);
        $this->assertDatabaseHas('addresses', ['address_line_3' => $updatedData['address_line_3']]);
        $this->assertDatabaseHas('addresses', ['address_line_4' => $updatedData['address_line_4']]);
        $this->assertDatabaseHas('addresses', ['city'           => $updatedData['city']]);
        $this->assertDatabaseHas('addresses', ['province'       => $updatedData['province']]);
        $this->assertDatabaseHas('addresses', ['postal_code'    => $updatedData['postal_code']]);
        $this->assertDatabaseHas('addresses', ['description'    => $updatedData['description']]);
        $this->assertDatabaseHas('addresses', ['comments'       => $updatedData['comments']]);
    }
}
