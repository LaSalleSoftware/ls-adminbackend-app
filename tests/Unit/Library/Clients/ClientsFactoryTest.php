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

namespace Tests\Unit\Library\Clients;

// LaSalle Software
use Lasallesoftware\Librarybackend\Profiles\Models\Client;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\TestCase;


class ClientsFactoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
    }

    /**
    * Test that the podcast_show factory inserts 50 records. 
    * 
    * @group Client
    * @group ClientClientsfactorytest
    * @group ClientClientsfactorytestPopulatewithfactory
    *
    * @return void
    */
   public function testPopulateWithFactory()
   {
        echo "\n**Now testing Tests\Unit\Library\Clients\ClientsFactoryTest**";

        factory(Client::class, 50)->create();

        $this->assertDatabaseCount('clients', 50);
   }
}