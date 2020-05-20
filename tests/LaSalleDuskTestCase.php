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

namespace Tests;

use Tests\DuskTestCase;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class LaSalleDuskTestCase extends DuskTestCase
{
    /**
     * Amount of time, in milliseconds, that a Dusk test should pause
     *
     * @return array
     */
    public function pause()
    {
        $base = env('LASALLE_DUSK_TEST_BASE_PAUSE_IN_MILLISECONDS');

        $shortest = $base;
        $short    = $shortest + 1000;
        $medium   = $short    + 1000;
        $long     = $medium   + 2500;
        $longest  = $long     + 2500;
        $debug    = $longest  + 2500;

        return [
            'shortest' => $shortest,
            'short'    => $short,
            'medium'   => $medium,
            'long'     => $long,
            'longest'  => $longest,
            'debug'    => $debug,
        ];
    }

    public function tearDown(): void
    {
        parent::tearDown();

        // https://github.com/fzaninotto/Faker/issues/1125
        // https://www.php.net/manual/en/function.gc-collect-cycles.php
        // https://stackoverflow.com/questions/2461762/force-freeing-memory-in-php
        gc_enable();
        gc_collect_cycles();
    }
}
