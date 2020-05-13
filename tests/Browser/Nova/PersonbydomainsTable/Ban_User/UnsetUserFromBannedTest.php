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

namespace Tests\Browser\Nova\PersonbydomainsTable\Ban_User;

// LaSalle Software classes
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Authentication\Models\Personbydomain;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

// Third Party Classes
use Carbon\CarbonImmutable;

class UnsetUserFromBannedTestTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
     * Test that a banned personbydomain can be un-banned in the Nova personbydomain resource form
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainBanuser
     * @group novaPersonbydomainBanuserUnsetuserfrombanned
     * @group novaPersonbydomainBanuserUnsetuserfrombannedDisablebaninnova
     */
    public function testDisableBanInNova()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Ban_User\UnsetUserFromBannedTestTest**";

        $now      = CarbonImmutable::now();
        $comments = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

        DB::table('personbydomains')
              ->where('id', 5)
              ->update([
                  'banned_enabled'  => 1,
                  'banned_at'       => $now,
                  'banned_comments' => $comments,
        ]);

        $personbydomain = Personbydomain::where('id',5)->first();
        $this->assertEquals($personbydomain->id, 5);
        $this->assertEquals($personbydomain->banned_enabled, 1);
        //$this->assertEquals($personbydomain->banned_at, $now);
        $this->assertEquals($personbydomain->banned_comments, $comments);



        $personTryingToLogin = $this->loginOwnerBobBloom;
        $pause               = $this->pause();
        
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomains')
                ->pause($pause['long'])
                ->assertSee('Create Personbydomain')
                ->assertVisible('@5-row')
                ->assertVisible('@5-edit-button')
                ->click('@5-edit-button')
                ->pause($pause['long'])
                ->assertSee('Update Personbydomain')
                ->uncheck('Banned')
                ->click('@update-button')
                ->pause($pause['long'])
                ->assertSee('Personbydomain Details')                
            ;
        });
        
        $personbydomain = Personbydomain::where('id',5)->first();
        $this->assertEquals($personbydomain->id, 5);
        $this->assertEquals($personbydomain->banned_enabled, 0);
        $this->assertNull($personbydomain->banned_at);
        $this->assertEquals($personbydomain->banned_comments, $comments);      
    }
}
