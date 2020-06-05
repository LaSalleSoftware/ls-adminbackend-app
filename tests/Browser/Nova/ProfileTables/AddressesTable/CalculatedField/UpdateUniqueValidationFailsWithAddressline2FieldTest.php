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

namespace Tests\Browser\Nova\ProfileTables\AddressesTable\CalculatedField;

// LaSalle Software classes
use Tests\LaSalleDuskTestCase;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateUniqueValidationFailsWithAddressline2FieldTest extends LaSalleDuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $newData;
    protected $updatedData;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');

        $this->personTryingToLogin = [
            'email' => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->testPassesData = [
            'address_line_1' => 'South LaSalle Street',
            'city' => 'Chicago',
            'province' => 'IL',
            'country' => 'US',
            'postal_code' => '60696',
            'lookup_address_type_id' => 1,
        ];

        $this->testFailsData = [
            'address_line_1' => '328 North Dearborn Street',
            'city' => 'Chicago',
            'province' => 'IL',
            'country' => 'US',
            'postal_code' => '60654',
            'lookup_address_type_id' => 6,
        ];
    }

    /**
     * Test that an update passes when a value is entered in a null address line of an address
     * that currently exists. This should cause the new address to be unique
     *
     * @group nova
     * @group novaprofiletables
     * @group novaaddress
     * @group novaaddresscalculatedfield
     */
    public function testUpdateUniqueValidationFailsWithAddressline2Field()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\AddressesTable\CalculatedField\UpdateUniqueValidationFailsWithAddressline2FieldTest**";

        $personTryingToLogin = $this->personTryingToLogin;

        // going to use the data in the "testFails" var,
        // and then enter a value in the formerly null "address_line_2" field
        $testFailsData = $this->testFailsData;
        $pause         = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $testFailsData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->clickLink('Addresses')
                ->pause($pause['long'])
                ->click('@1-edit-button')
                ->waitFor('@update-button')
                ->type('@address_line_1', $testFailsData['address_line_1'])
                ->pause($pause['long'])
                ->keys('@address_line_1', '{enter}')
                // enter a value in the currently null "address_line_2" field
                // which makes this address a completely new address; ie, *not* unique
                ->type('@address_line_2', 'in the loop')
                ->type('@postal_code', $testFailsData['postal_code'])   // the wrong zip code is entered automatically, so need to override it with what we want
                ->pause($pause['long'])
                ->click('@update-button')
                ->pause($pause['long'])
                ->assertSee('Address Details')
            ;
        });

        // the database should have this value in the database
        $address_calculated_should_be_in_the_database = '328 North Dearborn Street, in the loop, Chicago, IL, US  60654';
        $this->assertDatabaseHas('addresses', ['address_calculated' => $address_calculated_should_be_in_the_database]);

        // the database should *not* have this value in the database because we just did an update,
        // so the address_calculated field was just overwritten (well, supposed to be overwritten!)
        $address_calculated_should_not_be_in_the_database = '328 North Dearborn Street, Chicago, IL, US  60654';
        $this->assertDatabaseMissing('addresses',
            ['address_calculated' => $address_calculated_should_not_be_in_the_database]
        );
    }
}
