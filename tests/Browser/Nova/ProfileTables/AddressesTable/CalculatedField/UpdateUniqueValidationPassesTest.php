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
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateUniqueValidationPassesTest extends LaSalleDuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $newData;
    protected $updatedData;

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
     * Test that an update succeeds when the address_calculated field is unique
     *
     * @group nova
     * @group novaprofiletables
     * @group novaaddress
     * @group novaaddresscalculatedfield
     */
    public function testUpdateUniqueValidationPasses()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\AddressesTable\CalculatedField\UpdateUniqueValidationPassesTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $testPassesData      = $this->testPassesData;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $testPassesData, $pause) {
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
                ->type('@address_line_1', $testPassesData['address_line_1'])
                ->pause($pause['long'])
                ->keys('@address_line_1', '{enter}')
                ->click('@update-button')
                ->pause($pause['long'])
                ->assertSee('Address Details');
        });
    }
}
