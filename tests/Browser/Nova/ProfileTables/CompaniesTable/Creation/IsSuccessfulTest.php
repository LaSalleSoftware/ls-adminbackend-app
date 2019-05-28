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
 * @link       https://lasallesoftware.ca \lookup_social_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\ProfileTables\CompaniesTable\Creation;

// LaSalle Software classes
use Lasallesoftware\Library\Profiles\Models\Company;
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IsSuccessfulTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $newData;

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
            'name'        => 'Savoy Ballroom',
            'description' => 'The heartbeat of Harlem',
            'comments'    => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas accumsan lacus. Quam lacus suspendisse faucibus interdum posuere lorem ipsum dolor sit. Diam quam nulla porttitor massa id neque aliquam vestibulum. Imperdiet proin fermentum leo vel orci porta non pulvinar. Consequat ac felis donec et. Eget velit aliquet sagittis id consectetur purus ut faucibus pulvinar. Tincidunt ornare massa eget egestas purus viverra accumsan. Lectus quam id leo in vitae turpis massa. Et egestas quis ipsum suspendisse. Eu nisl nunc mi ipsum faucibus vitae aliquet. Pulvinar mattis nunc sed blandit libero volutpat sed. Duis tristique sollicitudin nibh sit amet commodo nulla.',
            'profile'     => 'Interdum posuere lorem ipsum dolor sit. Risus commodo viverra maecenas accumsan lacus. Quam lacus suspendisse faucibus interdum posuere lorem ipsum dolor sit. Diam quam nulla porttitor massa id neque aliquam vestibulum. Imperdiet proin fermentum leo vel orci porta non pulvinar. Consequat ac felis donec et. Eget velit aliquet sagittis id consectetur purus ut faucibus pulvinar. Tincidunt ornare massa eget egestas purus viverra accumsan. Lectus quam id leo in vitae turpis massa. Et egestas quis ipsum suspendisse. Eu nisl nunc mi ipsum faucibus vitae aliquet. Pulvinar mattis nunc sed blandit libero volutpat sed. Duis tristique sollicitudin nibh sit amet commodo nulla.',
        ];
    }

    /**
     * Test that the creation is successful
     *
     * @group nova
     * @group novacompany
     * @group novacompanycreation
     * @group novacompanycreationissuccessful
     */
    public function testCreateNewRecordIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\CompaniesTable\Creation\IsSuccessfulTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $newData             = $this->newData;
        $pause               = $this->pause;

        $this->browse(function (Browser $browser) use ($personTryingToLogin, $newData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause)
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Companies')
                ->waitFor('@create-button')
                ->click('@create-button')
                ->pause($pause)
                ->assertSee('New Company')
                ->type('@name',        $newData['name'])
                ->type('@description', $newData['description'])
                ->type('@comments',    $newData['comments'])
                ->type('@profile',     $newData['profile'])
                ->click('@create-button')
                ->pause($pause)
                ->assertSee('Company Details')
            ;

            $company = Company::orderBy('id', 'desc')->first();
            $uuid    =    Uuid::orderby('id', 'desc')->first();

            $browser->assertPathIs('/nova/resources/companies/'.$company->id);
            $this->assertEquals($newData['name'],        $company->name);
            $this->assertEquals($newData['description'], $company->description);
            $this->assertEquals($newData['comments'],    $company->comments);
            $this->assertEquals($newData['profile'],     $company->profile);

            $this->assertEquals($uuid->uuid, $company->uuid);
            $this->assertEquals($uuid->lasallesoftware_event_id, 7);

        });

        // these assertions are not needed, but still getting a feel for Dusk so here they are
        $this->assertDatabaseHas('companies', ['name'        => $newData['name']]);
        $this->assertDatabaseHas('companies', ['description' => $newData['description']]);
        $this->assertDatabaseHas('companies', ['comments'    => $newData['comments']]);
        $this->assertDatabaseHas('companies', ['profile'     => $newData['profile']]);
    }
}
