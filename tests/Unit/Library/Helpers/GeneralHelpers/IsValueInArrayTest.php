<?php

/**
 * This file is part of the Lasalle Software Basic Frontend App
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
 * @link       https://lasallesoftware.ca Blog, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/lsv2-basicfrontend-app Packagist
 * @link       https://github.com/lasallesoftware/lsv2-basicfrontend-app GitHub
 *
 */

namespace Tests\Unit\Library\Helpers\GeneralHelpers;

// Laravel classes
use Tests\TestCase;


class IsValueInArrayTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Does a value exist within an array?
     *
     * Using IP addresses because Lasallesoftware\Librarybackend\Helpers\GeneralHelpers\isValueInArray() exists for the
     * Lasallesoftware\Librarybackend\Firewall\Http\Middleware\Whitelist middleware.
     *
     * @group Library
     * @group LibraryHelpers
     * @group LibraryHelpersGeneralhelpers
     * @group LibraryHelpersGeneralhelpersIsvalueinarray
     * @group LibraryHelpersGeneralhelpersIsvalueinarrayValueexistsinthearray
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testValueExistsInTheArray()
    {
        echo "\n**Now testing Tests\Unit\Library\Helpers\GeneralHelpers\IsValueInArrayTest**";

        // Arrange
        $needle = '151.101.193.154';
        $haystack = [
            '151.101.193.153',
            '151.101.193.154',
            '151.101.193.155'
        ];

        $generalHelpers = $this->getMockForTrait('Lasallesoftware\Librarybackend\Helpers\GeneralHelpers');

        // Act
        $result = $generalHelpers->isValueInArray($needle, $haystack);

        // Assert
        $this->assertTrue($result, '***The needle was not found in the haystack, and it should have been found!***');
    }

    /**
     * Does a value NOT exist within an array?
     *
     * Using IP addresses because Lasallesoftware\Librarybackend\Helpers\GeneralHelpers\isValueInArray() exists for the
     * Lasallesoftware\Librarybackend\Firewall\Http\Middleware\Whitelist middleware.
     *
     * @group Library
     * @group LibraryHelpers
     * @group LibraryHelpersGeneralhelpers
     * @group LibraryHelpersGeneralhelpersIsvalueinarray
     * @group LibraryHelpersGeneralhelpersIsvalueinarrayValuenotexistinthearray
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testValueNotExistInTheArray()
    {
        // Arrange
        $needle = '151.101.193.150';
        $haystack = [
            '151.101.193.153',
            '151.101.193.154',
            '151.101.193.155'
        ];

        $generalHelpers = $this->getMockForTrait('Lasallesoftware\Librarybackend\Helpers\GeneralHelpers');

        // Act
        $result = $generalHelpers->isValueInArray($needle, $haystack);

        // Assert
        $this->assertFalse($result, '***The needle was found in the haystack, and it should NOT have been found!***');
    }
}
