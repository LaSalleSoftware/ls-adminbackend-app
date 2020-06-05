<?php

namespace Tests;

// Use my custom trait for my solution to text trix input fields
use Lasallesoftware\Librarybackend\Dusk\LaSalleProvidesBrowser;

// Use my custom trait for stuff that might be helpful for any of my Dusk tests
use Lasallesoftware\Librarybackend\Dusk\LaSalleHelpfulStuffForDusk;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication, LaSalleProvidesBrowser, LaSalleHelpfulStuffForDusk;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--window-size=1920,1080',
            //'--window-size=1080,608',
            '--no-sandbox',
            '--verbose'
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }
}
