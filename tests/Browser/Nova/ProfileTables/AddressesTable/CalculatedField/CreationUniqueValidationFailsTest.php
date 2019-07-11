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

namespace Tests\Browser\Nova\ProfileTables\AddressesTable\CalculatedField;

// LaSalle Software classes
use Lasallesoftware\Library\Dusk\LaSalleBrowser;
use Tests\Browser\LaSalleDuskTestCase;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreationUniqueValidationFailsTest extends LaSalleDuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $newData;
    protected $updatedData;

    /*
     * Dusk will pause its browser traversal by this value, in ms
     *
     * @var int
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

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
     * Test that a creation fails when the address_calculated field is not unique
     *
     * @group nova
     * @group novaprofiletables
     * @group novaaddress
     * @group novaaddresscalculatedfield
     * @group novaaddresscalculatedfieldcreationuniquevalidationfails
     */
    public function testCreationUniqueValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\AddressesTable\CalculatedField\CreationUniqueValidationFailsTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $testFailsData       = $this->testFailsData;
        $pause               = $this->pause;

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $testFailsData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Addresses')
                ->waitFor('@1-row')
                ->click('@create-button')
                ->pause($pause['medium'])
                ->assertSee('Create Address')
                ->type('@address_line_1', $testFailsData['address_line_1'])
                ->pause($pause['short'])
                ->keys('@address_line_1', '{enter}')
                ->select('@lookup_address_type', $testFailsData['lookup_address_type_id'])
                ->pause($pause['short'])
                ->click('@create-button')
                ->pause($pause['medium'])
                ->assertSee('This address already exists')
            ;
        });
    }
}
