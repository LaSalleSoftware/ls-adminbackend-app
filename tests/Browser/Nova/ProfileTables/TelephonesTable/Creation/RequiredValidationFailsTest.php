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
 * @link       https://lasallesoftware.ca \lookup_social_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\ProfileTables\TelephonesTable\Creation;

// LaSalle Software
use Tests\LaSalleDuskTestCase;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

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

        $this->artisan('lslibrarybackend:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->newData = [
            'country_code'             => null,
            'area_code'                => '(555)',
            'telephone_number'         => '222-2222',
            'extension'                => null,
            'lookup_telephone_type_id' => 1,
            'description'              => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas',
            'comments'                 => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas.',
        ];
    }

    /**
     * Test that the creation fails when the area_code field is not specified.
     *
     * @group nova
     * @group novaprofiletables
     * @group novatelephone
     * @group novatelephonecreation
     * @group novatelephonecreationrequiredvalidationfails
     * @group novatelephonecreationrequiredvalidationfailsareacodefield
     */
    public function testRequiredAreacodeFieldValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\TelephonesTable\Creation\RequiredValidationFailsTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $newData             = $this->newData;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $newData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->clickLink('Telephone Numbers')
                ->pause($pause['long'])
                ->assertVisible('@1-row')
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('Create Telephone Number')
                ->assertSelectHasOptions('@lookup_telephone_type', [1,2,3,4])
                //->type('@country_code',            $newData['country_code'])  **commented out because the default should be 1**
                //->type('@area_code',               $newData['area_code'])
                ->type('@telephone_number',        $newData['telephone_number'])
                ->type('@extension',               $newData['extension'])
                ->select('@lookup_telephone_type', $newData['lookup_telephone_type_id'])
                ->type('@description',             $newData['description'])
                ->type('@comments',                $newData['comments'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('The area code field is required')
            ;
        });
    }

    /**
     * Test that the creation fails when the area_code field is not specified.
     *
     * @group nova
     * @group novaprofiletables
     * @group novatelephone
     * @group novatelephonecreation
     * @group novatelephonecreationrequiredvalidationfails
     * @group novatelephonecreationrequiredvalidationfailstelephonenumberfield
     */
    public function testRequiredTelephonenumberFieldValidationFails()
    {
        $personTryingToLogin = $this->personTryingToLogin;
        $newData             = $this->newData;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $newData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->clickLink('Telephone Numbers')
                ->pause($pause['long'])
                ->assertVisible('@1-row')
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('Create Telephone Number')
                ->assertSelectHasOptions('@lookup_telephone_type', [1,2,3,4])
                //->type('@country_code',            $newData['country_code'])  **commented out because the default should be 1**
                ->type('@area_code',               $newData['area_code'])
                //->type('@telephone_number',        $newData['telephone_number'])
                ->type('@extension',               $newData['extension'])
                ->select('@lookup_telephone_type', $newData['lookup_telephone_type_id'])
                ->type('@description',             $newData['description'])
                ->type('@comments',                $newData['comments'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('The telephone number field is required')
            ;
        });
    }


    /**
     * Test that the creation fails when the country_code field is not specified.
     *
     * HAHAHA! THIS CANNOT HAPPEN BECAUSE THE COUNTRY_CODE FIELD DEFAULTS TO ONE IN THE CREATION FORM.
     * SO... TESTING THAT THIS CREATION ACTUALLY HAPPENS, *NOT* FAILS -- SINCE IT IS NOT SUPPOSED TO FAIL!
     *
     * @group nova
     * @group novaprofiletables
     * @group novatelephone
     * @group novatelephonecreation
     * @group novatelephonecreationrequiredvalidationfails
     * @group novatelephonecreationrequiredvalidationfailscountrycodefield
     */
    public function testRequiredCountrycodeFieldValidationFails()
    {
        $personTryingToLogin = $this->personTryingToLogin;
        $newData             = $this->newData;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $newData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->clickLink('Telephone Numbers')
                ->pause($pause['long'])
                ->assertVisible('@1-row')
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('Create Telephone Number')
                ->assertSelectHasOptions('@lookup_telephone_type', [1,2,3,4])
                //->type('@country_code',            $newData['country_code'])  **commented out because the default should be 1**
                ->type('@area_code',               $newData['area_code'])
                ->type('@telephone_number',        $newData['telephone_number'])
                ->type('@extension',               $newData['extension'])
                ->select('@lookup_telephone_type', $newData['lookup_telephone_type_id'])
                ->type('@description',             $newData['description'])
                ->type('@comments',                $newData['comments'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('Telephone Number Details')
            ;
        });
    }
}
