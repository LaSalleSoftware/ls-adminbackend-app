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

namespace Tests\Browser\Nova\BlogTables\AdminForms\Postupdates\Creation;

// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RequiredTitleValidationFailsTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that a message displays when the title field is left blank
     *
     * @group nova
     * @group novablogtablesadminforms
     * @group novablogtablesadminformspostupdates
     * @group novablogtablesadminformspostupdatescreation
     * @group novablogtablesadminformspostupdatescreationrequiredtitlevalidationfails
     */
    public function testRequiredTitleValidationFails()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\AdminForms\Postupdates\Creation\TestRequiredTitleValidationFails**";

        $login             = $this->loginOwnerBobBloom;
        $pause             = $this->pause();
        $newPostupdateData = $this->newPostupdateData;

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $newPostupdateData) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->clickLink('Post Updates')
                ->pause($pause['long'])
                ->assertSee('Create Post Update')
                ->clickLink('Create Post Update')
                ->pause($pause['long'])
                ->assertSee('Create Post Update')


                // The following is required to cope with the drop-down being searchable. So we cannot use the usual
                // ->select('@posts', 2). Instead, we have to go through the literal keystrokes.
                // Thank you to https://github.com/laravel/nova-dusk-suite/blob/10e02ff765a37771ae6436c112b93f6dab1819b9/tests/Browser/Pages/HasSearchableRelations.php
                ->click('[dusk="posts-search-input"]')
                ->pause($pause['long'])
                ->type('[dusk="posts-search-input"] input', $newPostupdateData['post_title'])
                ->pause($pause['long'])
                ->keys('[dusk="posts-search-input"] input', ['{enter}'])


                // continue!
                //->type('@title',           $newPostupdateData['title'])    <== well, we are testing when this is blank!
                ->typeTrix('trix-content', $newPostupdateData['content'])
                ->type('@publish_on',  $newPostupdateData['publish_on'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('The title field is required')
            ;
        });
    }
}
