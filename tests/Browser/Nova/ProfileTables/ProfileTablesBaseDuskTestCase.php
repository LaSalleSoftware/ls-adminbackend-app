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

namespace Tests\Browser\Nova\ProfileTables;

// LaSalle Software
use Tests\LaSalleDuskTestCase;

// Laravel Facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProfileTablesBaseDuskTestCase extends LaSalleDuskTestCase
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



    public function insertAddressesRecordForTesting()
    {
        DB::table('addresses')->insert([
            'lookup_address_type_id' => 5,
            'address_calculated'     => 'A specially inserted record for testing!',
            'address_line_1'         => 'A specially inserted record for testing!',
            'address_line_2'         => 'A specially inserted record for testing!',
            'address_line_3'         => 'A specially inserted record for testing!',
            'address_line_4'         => 'A specially inserted record for testing!',
            'city'                   => 'Chicago',
            'province'               => 'IL',
            'country'                => 'US',
            'postal_code'            => '60654',
            'latitude'               => 41.89290000,
            'longitude'              => -87.62970000,
            'description'            => null,
            'comments'               => null,
            'profile'                => null,
            'featured_image'         => null,
            'map_link'               => '',
            //'uuid'                   => 'A specially inserted record for testing!',  // Get an error since not in UUIDS table, not needed for test anyway
            'created_at'             => Carbon::now(),
            'created_by'             => 1,
            'updated_at'             => null,
            'updated_by'             => null,
            'locked_at'              => null,
            'locked_by'              => null,
        ]);
    }

    public function insertCompaniesRecordForTesting()
    {
        DB::table('addresses')->insert([
            'lookup_address_type_id' => 5,
            'address_calculated'     => 'A specially inserted record for testing!',
            'address_line_1'         => 'A specially inserted record for testing!',
            'address_line_2'         => 'A specially inserted record for testing!',
            'address_line_3'         => 'A specially inserted record for testing!',
            'address_line_4'         => 'A specially inserted record for testing!',
            'city'                   => 'Chicago',
            'province'               => 'IL',
            'country'                => 'US',
            'postal_code'            => '60654',
            'latitude'               => 41.89290000,
            'longitude'              => -87.62970000,
            'description'            => null,
            'comments'               => null,
            'profile'                => null,
            'featured_image'         => null,
            'map_link'               => '',
            //'uuid'                   => 'A specially inserted record for testing!',  // Get an error since not in UUIDS table, not needed for test anyway
            'created_at'             => Carbon::now(),
            'created_by'             => 1,
            'updated_at'             => null,
            'updated_by'             => null,
            'locked_at'              => null,
            'locked_by'              => null,
        ]);
    }

    public function insertEmailsRecordForTesting()
    {
        DB::table('addresses')->insert([
            'lookup_email_type_id'  => 1,
            'email_address'         => 'test@test.test',
            'description'           => null,
            'comments'              => null,
            //'uuid'                   => 'A specially inserted record for testing!',  // Get an error since not in UUIDS table, not needed for test anyway
            'created_at'            => Carbon::now(),
            'created_by'            => 1,
            'updated_at'            => null,
            'updated_by'            => null,
            'locked_at'             => null,
            'locked_by'             => null,
        ]);
    }

    public function insertPersonsRecordForTesting()
    {
        DB::table('addresses')->insert([
            'name_calculated' => 'Person for testing',
            'salutation'      => 'Mr.',
            'first_name'      => 'First name for testing',
            'middle_name'     => 'Middle name for testing',
            'surname'         => 'Surname for testing',
            'position'        => 'Position for testing',
            'description'     => 'Description for testing',
            'comments'        => 'Comments for testing',
            'profile'         => null,
            'featured_image'  => null,
            'birthday'        => null,
            'anniversary'     => null,
            'deceased'        => null,
            'comments_date'   => null,
            //'uuid'           => 'A specially inserted record for testing!',  // Get an error since not in UUIDS table, not needed for test anyway
            'created_at'      => Carbon::now(),
            'created_by'      => 1,
            'updated_at'      => null,
            'updated_by'      => null,
            'locked_at'       => null,
            'locked_by'       => null,
        ]);
    }

    public function insertSocialsRecordForTesting()
    {
        DB::table('addresses')->insert([
            'lookup_social_type_id' => 1,
            'url'                   => 'https://social.social',
            'description'           => 'description for testing',
            'comments'              => 'comment for testing',
            //'uuid'                   => 'A specially inserted record for testing!',  // Get an error since not in UUIDS table, not needed for test anyway
            'created_at'            => Carbon::now(),
            'created_by'            => 1,
            'updated_at'            => null,
            'updated_by'            => null,
            'locked_at'             => null,
            'locked_by'             => null,
        ]);
    }

    public function insertTelephonesRecordForTesting()
    {
        DB::table('addresses')->insert([
            'lookup_telephone_type_id' => 1,
            'telephone_calculated'     => '1 (555) 123-4567',
            'country_code'             => 1,
            'area_code'                => 555,
            'telephone_number'         => 1234567,
            'extension'                => null,
            'description'              => 'description for testing',
            'comments'                 => 'comment for testing',
            //'uuid'                   => 'A specially inserted record for testing!',  // Get an error since not in UUIDS table, not needed for test anyway
            'created_at'               => Carbon::now(),
            'created_by'               => 1,
            'updated_at'               => null,
            'updated_by'               => null,
            'locked_at'                => null,
            'locked_by'                => null,
        ]);
    }

    public function insertWebsitesRecordForTesting()
    {
        DB::table('addresses')->insert([
            'lookup_website_type_id' => 1,
            'url'                    => 'https://www.mlb.com/bluejays',
            'description'            => 'description for testing',
            'comments'               => 'comment for testing',
            //'uuid'                   => 'A specially inserted record for testing!',  // Get an error since not in UUIDS table, not needed for test anyway
            'created_at'             => Carbon::now(),
            'created_by'             => 1,
            'updated_at'             => null,
            'updated_by'             => null,
            'locked_at'              => null,
            'locked_by'              => null,
        ]);
    }
}
