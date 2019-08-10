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

namespace Tests\Browser\Nova\PersonbydomainsTable;

// LaSalle Software
use Tests\Browser\LaSalleDuskTestCase;

// Laravel Facade
use Illuminate\Support\Facades\DB;

class PersonbydomainsTableBaseDuskTest extends LaSalleDuskTestCase
{
    public $loginOwnerBobBloom = [
        'email'    => 'bob.bloom@lasallesoftware.ca',
        'password' => 'secret',
    ];

    public $loginSuperadminDomain1 = [
        'email'    => 'sidney.bechet@blogtest.ca',
        'password' => 'secret',
    ];

    public $loginAdminDomain1 = [
        'email'    => 'robert.johnson@blogtest.ca',
        'password' => 'secret',
    ];

    public $newPersonData = [
        'name_calculated' => 'John Lee Hooker',
        'first_name'      => 'John Lee',
        'surname'         => 'Hooker',
        'uuid'            => 'created during a Dusk test',
        'created_at'      => '2019-07-21 18:27:26',
        'created_by'      => 1,
    ];

    public $newEmailData = [
        'lookup_email_type_id' => 4,
        'email_address'        => 'johnleehooker@blues.com',
        'description'          => 'Lorem ipsum dolor sit amet consectetur adipiscing elit per, est grav',
        'comments'             => 'Lorem ipsum dolor sit amet consectetur adipiscing elit per, est grav',
        'uuid'                 => 'created during a Dusk test',
    ];

    public $newEmailListedInNovaServiceProvider = [
        'lookup_email_type_id' => 4,
        'email_address'        => 'satchmo@blues.com',
        'description'          => 'Lorem ipsum dolor sit amet consectetur adipiscing elit per, est grav',
        'comments'             => 'Lorem ipsum dolor sit amet consectetur adipiscing elit per, est grav',
        'uuid'                 => 'created during a Dusk test',
    ];



    /**
     * Insert a test record into the emails database table.
     *
     * @return bool
     */
    public function insertTestRecordIntoEmailsTable()
    {
        return DB::table('emails')->insert([
            'lookup_email_type_id' => $this->newEmailData['lookup_email_type_id'],
            'email_address'        => $this->newEmailData['email_address'],
            'description'          => $this->newEmailData['description'],
            'comments'             => $this->newEmailData['comments'],
            'uuid'                 => $this->newEmailData['uuid'],
            'created_at'           => now(),
            'created_by'           => 1,
        ]);
    }

    /**
     * Insert a test record into the persons database table.
     *
     * @return bool
     */
    public function insertTestRecordIntoPersonsTable()
    {
        return DB::table('persons')->insert([
            'name_calculated' => $this->newPersonData['name_calculated'],
            'first_name'      => $this->newPersonData['first_name'],
            'surname'         => $this->newPersonData['surname'],
            'uuid'            => $this->newPersonData['uuid'],
            'created_at'      => now(),
            'created_by'      => 1,
        ]);
    }

    /**
     * Insert a test record into the person_email database table.
     *
     * @return bool
     */
    public function insertTestRecordIntoPerson_emaiTable()
    {
        return DB::table('person_email')->insert([
            'person_id' => DB::table('persons')->where('name_calculated', $this->newPersonData['name_calculated'])->pluck('id')->first(),
            'email_id'  => DB::table('emails')->where('email_address', $this->newEmailData['email_address'])->pluck('id')->first()
        ]);
    }

    /*
     * Insert a test record into the personbydomains database table.
     *
     * @return bool
     */
    public function insertTestRecordIntoPersonbydomainTable()
    {
        return DB::table('personbydomains')->insert([
            'person_id'              => DB::table('persons')->where('name_calculated', $this->newPersonData['name_calculated'])->pluck('id')->first(),
            'person_first_name'      => $this->newPersonData['first_name'],
            'person_surname'         => $this->newPersonData['surname'],
            'email'                  => $this->newEmailData['email_address'],
            'password'               => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret,
            'installed_domain_id'    => 1,
            'installed_domain_title' => 'hackintosh.lsv2-adminbackend-app.com',
            'uuid'                   => $this->newPersonData['uuid'],
            'created_at'             => now(),
            'created_by'             => 1,
        ]);
    }

    /**
     * Change the installed_domain_id's of some existing records. This will save the trouble of creating fresh records!
     *
     * @return void
     */
    public function updateInstalleddomainid()
    {
        DB::table('personbydomains')->where('id', 2)->update(['installed_domain_id' => 3, 'installed_domain_title' => 'pretendfrontend.com']);
        DB::table('personbydomains')->where('id', 3)->update(['installed_domain_id' => 3, 'installed_domain_title' => 'pretendfrontend.com']);
        //DB::table('personbydomains')->where('id', 4)->update(['installed_domain_id' => 4, 'installed_domain_title' => 'anotherpretendfrontend.com']);
        DB::table('personbydomains')->where('id', 5)->update(['installed_domain_id' => 4, 'installed_domain_title' => 'anotherpretendfrontend.com']);
    }
}
