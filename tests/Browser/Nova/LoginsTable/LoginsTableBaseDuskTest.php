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

namespace Tests\Browser\Nova\LoginsTable;

// LaSalle Software
use Tests\Browser\LaSalleDuskTestCase;

// Laravel Facade
use Illuminate\Support\Facades\DB;

class LoginsTableBaseDuskTest extends LaSalleDuskTestCase
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


    /**
     * Insert test records into the logins database table.
     *
     * Exclude the owner login because during the Dusk tests, the owner is logged in and has a logins record from
     * that login.
     *
     * Just in case I put in a feature later that limits the number of login records a single
     * personbydomain_id can have, I am doing this exclusion.
     *
     * @return bool
     */
    public function insertTestRecordIntoLoginsExcludeOwnerLoginTable()
    {
        $now = now();

        DB::table('logins')->insert([
            'personbydomain_id' => 2,
            'token'             => 'token_for_blues_boy_king',
            'uuid'              => 'uuid_for_blues_boy_king',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);

        DB::table('logins')->insert([
            'personbydomain_id' => 3,
            'token'             => 'token_for_stevie_ray_vaughan',
            'uuid'              => 'uuid_for_stevie_ray_vaughan',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);

        DB::table('logins')->insert([
            'personbydomain_id' => 4,
            'token'             => 'token_for_sidney_bechet',
            'uuid'              => 'uuid_for_sidney_bechet',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);

        DB::table('logins')->insert([
            'personbydomain_id' => 5,
            'token'             => 'token_for_robert_johnson',
            'uuid'              => 'uuid_for_robert_johnson',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);


        // change BB King to an owner
        DB::table('personbydomain_lookup_roles')
            ->where('personbydomain_id', 2)
            ->update(['lookup_role_id' => 1])
        ;

        // change Sidney Bechet to domain #2
        DB::table('personbydomains')
            ->where('id', 4)
            ->update(['installed_domain_id' => 2])
        ;

        // change Robert Johnson to domain #3
        DB::table('personbydomains')
            ->where('id', 5)
            ->update(['installed_domain_id' => 3])
        ;
    }

    /**
     * Insert test records into the logins database table.
     *
     * Exclude the super administrator login because during the Dusk tests, the owner is logged in and has a logins
     * record from that login.
     *
     * Just in case I put in a feature later that limits the number of login records a single
     * personbydomain_id can have, I am doing this exclusion.
     *
     * @return bool
     */
    public function insertTestRecordIntoLoginsExcludeSuperadminLoginTable()
    {
        $now = now();

        DB::table('logins')->insert([
            'personbydomain_id' => 1,
            'token'             => 'token_for_bob_bloom',
            'uuid'              => 'uuid_for_bob_bloom',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);

        DB::table('logins')->insert([
            'personbydomain_id' => 2,
            'token'             => 'token_for_blues_boy_king',
            'uuid'              => 'uuid_for_blues_boy_king',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);

        DB::table('logins')->insert([
            'personbydomain_id' => 3,
            'token'             => 'token_for_stevie_ray_vaughan',
            'uuid'              => 'uuid_for_stevie_ray_vaughan',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);

        DB::table('logins')->insert([
            'personbydomain_id' => 5,
            'token'             => 'token_for_robert_johnson',
            'uuid'              => 'uuid_for_robert_johnson',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);


        // change BB King to an owner
        DB::table('personbydomain_lookup_roles')
            ->where('personbydomain_id', 2)
            ->update(['lookup_role_id' => 1])
        ;

        // change Sidney Bechet to domain #2
        DB::table('personbydomains')
            ->where('id', 4)
            ->update(['installed_domain_id' => 2])
        ;

        // change Robert Johnson to domain #3
        DB::table('personbydomains')
            ->where('id', 5)
            ->update(['installed_domain_id' => 3])
        ;
    }

    /**
     * Insert test records into the logins database table.
     *
     * Exclude the admin login because during the Dusk tests, the owner is logged in and has a logins record from
     * that login.
     *
     * Just in case I put in a feature later that limits the number of login records a single
     * personbydomain_id can have, I am doing this exclusion.
     *
     * @return bool
     */
    public function insertTestRecordIntoLoginsExcludeAdminLoginTable()
    {
        $now = now();

        DB::table('logins')->insert([
            'personbydomain_id' => 1,
            'token'             => 'token_for_bob_bloom',
            'uuid'              => 'uuid_for_bob_bloom',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);

        DB::table('logins')->insert([
            'personbydomain_id' => 2,
            'token'             => 'token_for_blues_boy_king',
            'uuid'              => 'uuid_for_blues_boy_king',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);

        DB::table('logins')->insert([
            'personbydomain_id' => 3,
            'token'             => 'token_for_stevie_ray_vaughan',
            'uuid'              => 'uuid_for_stevie_ray_vaughan',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);

        DB::table('logins')->insert([
            'personbydomain_id' => 4,
            'token'             => 'token_for_sidney_bechet',
            'uuid'              => 'uuid_for_sidney_bechet',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);


        // change BB King to an owner
        DB::table('personbydomain_lookup_roles')
            ->where('personbydomain_id', 2)
            ->update(['lookup_role_id' => 1])
        ;

        // change Sidney Bechet to domain #2
        DB::table('personbydomains')
            ->where('id', 4)
            ->update(['installed_domain_id' => 2])
        ;

        // change Robert Johnson to domain #3
        DB::table('personbydomains')
            ->where('id', 5)
            ->update(['installed_domain_id' => 3])
        ;
    }
}
