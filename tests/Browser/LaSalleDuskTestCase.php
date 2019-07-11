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

namespace Tests\Browser;

use Tests\DuskTestCase;

class LaSalleDuskTestCase extends DuskTestCase
{
    /**
     * Amount of time Dusk should pause.
     *
     * @var array
     */
    public $pause_ORIGINAL = [
        'shortest' => 500,
        'short'    => 1500,
        'medium'   => 2500,
        'long'     => 5000,
        'longest'  => 7500,
        'debug'    => 10000,
    ];

    public $pause = [
        'shortest' => 500,
        'short'    => 2500,
        'medium'   => 3500,
        'long'     => 5000,
        'longest'  => 7500,
        'debug'    => 10000,
    ];
}
