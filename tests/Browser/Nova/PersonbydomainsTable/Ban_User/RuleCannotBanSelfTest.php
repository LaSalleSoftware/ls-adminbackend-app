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

namespace Tests\Browser\Nova\PersonbydomainsTable\Ban_User;

// LaSalle Software classes
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Authentication\Models\Personbydomain;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;


class RuleCannotBanSelfTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
     * Test rule that a personbydomain cannot ban themselves.
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainBanuser
     * @group novaPersonbydomainBanuserRulecannotbanself
     * @group novaPersonbydomainBanuserRulecannotbanselfRulecannotbanoneself
     */
    public function testRuleCannotBanOneself()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Ban_User\RuleCannotBanSelfTest**";

        $personTryingToLogin = $this->loginOwnerBobBloom;
        $pause               = $this->pause();
        
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomains')
                ->pause($pause['long'])
                ->assertSee('Create Personbydomain')
                ->assertVisible('@1-row')
                ->assertVisible('@1-edit-button')
                ->click('@1-edit-button')
                ->pause($pause['long'])
                ->assertSee('Update Personbydomain')
                ->check('Banned')
                ->click('@update-button')
                ->pause($pause['long'])
                ->assertSee('You cannot ban yourself')                
            ;
        });
       
    }
}
