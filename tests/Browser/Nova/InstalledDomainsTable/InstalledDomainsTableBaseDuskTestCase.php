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

namespace Tests\Browser\Nova\InstalledDomainsTable;

// LaSalle Software
use Tests\LaSalleDuskTestCase;

// Laravel facade
use Illuminate\Support\Facades\DB;

class InstalledDomainsTableBaseDuskTestCase extends LaSalleDuskTestCase
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


    public function insertJWTKeys()
    {
        DB::table('installed_domains_jwt_keys')->insert([
            'installed_domain_id' => 1,
            'key'                 => 'abcdefg',
            'enabled'             => 1,
            'created_at'          => now(),
            'created_by'          => 1
        ]);
    }
}
