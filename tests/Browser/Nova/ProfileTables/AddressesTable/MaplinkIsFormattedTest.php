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

namespace Tests\Browser\Nova\ProfileTables\AddressesTable;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Address;
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;


class MaplinkIsFormattedTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $updatedData;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->updatedData = [
            'map_link_null'       => null,
            'map_link_no_http'    => 'www.houseofblues.com/',
            'map_link_with_http'  => 'http://www.houseofblues.com/',
            'map_link_with_https' => 'https://www.houseofblues.com/',
        ];
    }

    /**
     * Test that an update is successful.
     *
     * SKIP THIS TEST! DUSK IS NOT OVER-RIDING THE EXISTING VALUE THE map_link FIELD WITH blank OR null
     * SO I'M JUST SKIPPING THIS ONE
     *
     * @group novaSKIP
     * @group novaaddressmaplinkSKIP
     */
    public function testMaplinkFieldIsNull()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\AddressesTable\MaplinkIsFormattedTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $updatedData         = $this->updatedData;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $updatedData) {

            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Addresses')
                ->waitFor('@1-row')
                ->assertVisible('@1-row')
                ->assertVisible('@1-edit-button')
                ->click('@1-edit-button')
                ->waitFor('@update-button')
                ->assertSee('Edit Address')
                ->type('@map_link', $updatedData['map_link_null'])
                //->pause(5000)
                ->click('@update-button')
                ->pause(5000)
                ->assertSee('Address Details')
            ;

            $address = Address::orderBy('id', 'desc')->first();
            $uuid    =    Uuid::orderby('id', 'desc')->first();

            $browser->assertPathIs('/nova/resources/addresses/'.$address->id);
            $this->assertEquals($updatedData['map_link_null'], $address->map_link);

            $this->assertEquals($uuid->uuid, $address->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 8);
        });

        // these assertions are not needed, but still getting a feel for Dusk so here they are
        $this->assertDatabaseHas('addresses', ['map_link' => $updatedData['map_link_null']]);
    }


    /**
     * Test that an update is successful.
     *
     * @group nova
     * @group novaaddressmaplink
     */
    public function testMaplinkFieldIsNotHttp()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\AddressesTable\MaplinkIsFormattedTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $updatedData         = $this->updatedData;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $updatedData) {

            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Addresses')
                ->waitFor('@1-row')
                ->assertVisible('@1-row')
                ->assertVisible('@1-edit-button')
                ->click('@1-edit-button')
                ->waitFor('@update-button')
                ->assertSee('Edit Address')
                ->type('@address_line_2', 'Soul Man')
                ->type('@map_link', $updatedData['map_link_no_http'])
                ->pause(5000)
                ->click('@update-button')
                ->pause(5000)
                ->assertSee('Address Details')
            ;

            $address = Address::orderBy('id', 'desc')->first();
            $uuid    =    Uuid::orderby('id', 'desc')->first();

            $browser->assertPathIs('/nova/resources/addresses/'.$address->id);
            $this->assertEquals('http://' . $updatedData['map_link_no_http'], $address->map_link);

            $this->assertEquals($uuid->uuid, $address->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 8);
        });

        // these assertions are not needed, but still getting a feel for Dusk so here they are
        $this->assertDatabaseHas('addresses', ['map_link' => 'http://' . $updatedData['map_link_no_http']]);
    }

    /**
     * Test that an update is successful.
     *
     * @group nova
     * @group novaaddressmaplink
     */
    public function testMaplinkFieldWithHttp()
    {
        $personTryingToLogin = $this->personTryingToLogin;
        $updatedData         = $this->updatedData;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $updatedData) {

            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Addresses')
                ->waitFor('@1-row')
                ->assertVisible('@1-row')
                ->assertVisible('@1-edit-button')
                ->click('@1-edit-button')
                ->waitFor('@update-button')
                ->assertSee('Edit Address')
                ->pause(5000)
                ->type('@address_line_2', 'Rawhide')
                ->type('@map_link', $updatedData['map_link_with_http'])
                ->click('@update-button')
                ->pause(5000)
                ->assertSee('Address Details')
            ;

            $address = Address::orderBy('id', 'desc')->first();
            $uuid    =    Uuid::orderby('id', 'desc')->first();

            $browser->assertPathIs('/nova/resources/addresses/'.$address->id);
            $this->assertEquals($updatedData['map_link_with_http'], $address->map_link);

            $this->assertEquals($uuid->uuid, $address->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 8);
        });

        // these assertions are not needed, but still getting a feel for Dusk so here they are
        $this->assertDatabaseHas('addresses', ['map_link' => $updatedData['map_link_with_http']]);
    }

    /**
     * Test that an update is successful.
     *
     * @group nova
     * @group novaaddress
     * @group novaaddressmaplink
     */
    public function testMaplinkFieldWithHttps()
    {
        $personTryingToLogin = $this->personTryingToLogin;
        $updatedData         = $this->updatedData;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $updatedData) {

            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(500)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Addresses')
                ->waitFor('@1-row')
                ->assertVisible('@1-row')
                ->assertVisible('@1-edit-button')
                ->click('@1-edit-button')
                ->waitFor('@update-button')
                ->assertSee('Edit Address')
                ->pause(5000)
                ->type('@address_line_2', 'Sweet Home')
                ->type('@map_link', $updatedData['map_link_with_https'])
                ->click('@update-button')
                ->pause(10000)
                ->assertSee('Address Details')
            ;

            $address = Address::orderBy('id', 'desc')->first();
            $uuid    =    Uuid::orderby('id', 'desc')->first();

            $browser->assertPathIs('/nova/resources/addresses/'.$address->id);
            $this->assertEquals($updatedData['map_link_with_https'], $address->map_link);

            $this->assertEquals($uuid->uuid, $address->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 8);
        });

        // these assertions are not needed, but still getting a feel for Dusk so here they are
        $this->assertDatabaseHas('addresses', ['map_link' => $updatedData['map_link_with_https']]);
    }
}
