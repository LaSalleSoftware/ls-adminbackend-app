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

namespace Tests\Browser\Nova\ProfileTables\PersonsTable\CalculatedField;

// LaSalle Software classes
use Tests\LaSalleDuskTestCase;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreationUniqueValidationFailsTest extends LaSalleDuskTestCase
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
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->testPassesData = [
            'saluation'   => '',
            'first_name'  => 'Ella',
            'middle_name' => 'Jane',
            'surname'     => 'Fitzgerald',
            'position'    => 'The First Lady of Song',
            'birthday'    => '',
            'anniversary' => '',
            'description' => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas',
            'comments'    => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas accumsan lacus. Quam lacus suspendisse faucibus interdum posuere lorem ipsum dolor sit.',
        ];

        $this->testFailsData = [
            'id'          => 4,
            'saluation'   => 'Mr.',
            'first_name'  => 'Stevie',
            'middle_name' => 'Ray',
            'surname'     => 'Vaughan',
            'position'    => '',
            'birthday'    => '',
            'anniversary' => '',
            'description' => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas',
            'comments'    => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas accumsan lacus. Quam lacus suspendisse faucibus interdum posuere lorem ipsum dolor sit.',
        ];
    }

    /**
     * Test that a creation fails when the name_calculated field is not unique
     *
     * @group nova
     * @group novaprofiletables
     * @group novaperson
     * @group novapersoncalculatedfield
     * @group novapersoncalculatedfieldcreationfails
     */
    public function testCreationUniqueValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\PersonsTable\CalculatedField\CreationUniqueValidationFailsTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $testFailsData       = $this->testFailsData;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $testFailsData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->clickLink('People')
                ->pause($pause['long'])
                ->assertVisible('@create-button')
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('Create Person')
                ->type('@first_name', $testFailsData['first_name'])
                ->type('@middle_name', $testFailsData['middle_name'])
                ->type('@surname', $testFailsData['surname'])
                ->type('@position', $testFailsData['position'])
                ->type('@description', $testFailsData['description'])
                ->type('@comments', $testFailsData['comments'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('This person already exists');
            ;
        });
    }
}
