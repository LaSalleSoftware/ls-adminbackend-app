<?php

/**
 * This file is part of the LaSalle Software Administrative Back-end application.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019-2021 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 *
 * @see       https://lasallesoftware.ca
 * @see       https://packagist.org/packages/lasallesoftware/ls-basicfrontend-app
 * @see       https://github.com/LaSalleSoftware/ls-adminbackend-app
 */

namespace App\Providers;

// Laravel framework classes
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
{
    public function boot()
    { 
        $this->app->booted(function () {

            $schedule = $this->app->make(Schedule::class);

            $schedule->command('auth:clear-resets')->dailyAt('04:00');
            $schedule->command('lslibrary:deleteexpiredlogins')->dailyAt('04:05');
            $schedule->command('lslibrary:deleteexpiredjwt')->dailyAt('04:10');
            $schedule->command('lslibrary:deleteexpireduuid')->dailyAt('04:15');
            $schedule->command('lslibrary:deleteactioneventsrecords')->dailyAt('04:20');

        });
    }

    public function register()
    {
        // blank on purpose
    }
}