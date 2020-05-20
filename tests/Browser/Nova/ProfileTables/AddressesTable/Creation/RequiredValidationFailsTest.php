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

namespace Tests\Browser\Nova\ProfileTables\AddressesTable\Creation;

// LaSalle Software classes
use Tests\LaSalleDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RequiredValidationFailsTest extends LaSalleDuskTestCase
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
     * Test that the creation fails when the address_line_1 field is not specified.
     *
     * @group nova
     * @group novaprofiletables
     * @group novaaddress
     * @group novaaddresscreationrequiredvalidationfails
     */
    public function testRequiredAddressFieldValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\AddressesTable\Creation\RequiredValidationFailsTest**";

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
                ->select('@lookup_address_type', $newData['lookup_address_type_id'])
                ->type('@description', $newData['description'])
                ->type('@comments', $newData['comments'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('The address line 1 field is required')
            ;
        });
    }

    /**
     * Test that the creation fails when the address type field is not specified.
     *
     * @group nova
     * @group novaprofiletables
     * @group novaaddress
     * @group novaaddresscreationrequiredvalidationfails
     */
    public function testRequiredAddresstypeFieldValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\AddressesTable\Creation\RequiredValidationFailsTest**";

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
                //->select('@lookup_address_type', $newData['lookup_address_type_id'])
                ->type('@description', $newData['description'])
                ->type('@comments', $newData['comments'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('The lookup address type field is required')
            ;
        });
    }
}
