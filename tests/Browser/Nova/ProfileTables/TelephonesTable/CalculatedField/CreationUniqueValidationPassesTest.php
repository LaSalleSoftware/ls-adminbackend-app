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

namespace Tests\Browser\Nova\ProfileTables\TelephonesTable\CalculatedField;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Telephone;
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreationUniqueValidationPassesTest extends DuskTestCase
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
    protected $pause = 1500;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->newData = [
            'country_code'             => 1,
            'area_code'                => '(444)',
            'telephone_number'         => '234-5678',
            'extension'                => null,
            'lookup_telephone_type_id' => 1,
            'description'              => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas',
            'comments'                 => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas.',
        ];
    }

    /**
     * Test that a creation succeeds when the telephone_calculated field is unique
     *
     * @group nova
     * @group novatelephone
     * @group novatelephonecalculatedfield
     * @group novatelephonecalculatedfieldcreationuniquevalidationpasses
     */
    public function testCreationUniqueValidationPasses()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\TelephonesTable\CalculatedField\CreationUniqueValidationPassesTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $newData             = $this->newData;
        $pause               = $this->pause;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $newData, $pause ) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Telephone Numbers')
                ->waitFor('@create-button')
                ->assertVisible('@create-button')
                ->click('@create-button')
                ->pause($pause)
                ->assertSee('New Telephone Number')
                //->type('@country_code',            $newData['country_code'])  **commented out because the default should be 1**
                ->type('@area_code',               $newData['area_code'])
                ->type('@telephone_number',        $newData['telephone_number'])
                ->type('@extension',               $newData['extension'])
                ->select('@lookup_telephone_type', $newData['lookup_telephone_type_id'])
                ->type('@description',             $newData['description'])
                ->type('@comments',                $newData['comments'])
                ->click('@create-button')
                ->pause($pause)
                ->assertSee('Telephone Number Details')
            ;
        });
    }
}
