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

namespace Tests\Unit\Library\Uuid;

// LaSalle Software classes
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid as UuidModel;
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UuidTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrarybackend:customseed');
    }

    /**
     * The UUID should be 36 characters long. If not, the Ramsey package might have changed the UUID,
     * and the UUID could be longer than the 36char UUID database field
     *
     * @group Library
     * @group LibraryUuid
     * @group LibraryUuidUuid
     * @group LibraryUuidUuidUuidshouldbe36characterslong
     *
     * @return void
     */
    public function testUuidShouldBe36CharactersLong()
    {
        echo "\n**Now testing Tests\Unit\Library\Uuid\UuidTest**";

        // Arrange
        $uuidGenerator = $this->getMockBuilder(UuidGenerator::class)
            ->setMethods(null)
            //->disableOriginalConstructor()
            ->getMock()
        ;

        // Act
        $newUuid = $uuidGenerator->newUuid();

        // Assert
        $this->assertTrue(strlen($newUuid) === 36, '***The uuid string length should be 36***');
    }

    /**
     * Testing that the UUID is INSERTed into the database with the default values.
     * The default values are:
     *  lasallesoftware_event_id = 1
     *  comments = null
     *  created_by = 1
     *  created_at = now()
     *
     * @group Library
     * @group LibraryUuid
     * @group LibraryUuidUuid
     * @group LibraryUuidUuidInsertuuidwithfactoryvalues
     *
     * @return void
     */
    public function testInsertUuidWithFactoryValues()
    {
        // Arrange
        $uuidGenerator = $this->getMockBuilder(UuidGenerator::class)
            ->setMethods(null)
            //->disableOriginalConstructor()
            ->getMock()
        ;

        $uuid = factory(\Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid::class)->make([
            'comments' => null,
        ]);

        // Act
        $uuidGenerator->insertUuidIntoDatabase(
            $uuid['lasallesoftware_event_id'],
            $uuid['uuid'],
            $uuid['comments'],
            $uuid['created_by'],
            $uuid['created_at']
        );

        // Assert
        $record = \Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid::find(DB::getPdo()->lastInsertId());
        // The following gives a message for each failing test, which is handier than the above's message when a test(s) fail
        $this->assertTrue($record->lasallesoftware_event_id == 1,'***The uuid lasallesoftware_event_id from factory is 1***');
        $this->assertTrue($record->comments === null,'***The uuid comment should be null***');
        $this->assertTrue($record->created_by == 1,'***The uuid created_by factory is 1***');

        $this->assertTrue(is_int($record->lasallesoftware_event_id), "***The uuid lasallesoftware_event_id must be an integer***");
    }

    /**
     * Testing that the UUID is INSERTed into the database with no (null) comments.
     *
     * @group Library
     * @group LibraryUuid
     * @group LibraryUuidUuid
     * @group LibraryUuidUuidInsertuuidwithcomments
     *
     * @return void
     */
    public function testInsertUuidWithComments()
    {
        // Arrange
        $uuidGenerator = $this->getMockBuilder(UuidGenerator::class)
            ->setMethods(null)
            //->disableOriginalConstructor()
            ->getMock()
        ;
        $uuid = factory(\Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid::class)->make();

        // Act
        $uuidGenerator->insertUuidIntoDatabase(
            $uuid['lasallesoftware_event_id'],
            $uuid['uuid'],
            $uuid['comments'],
            $uuid['created_by'],
            $uuid['created_at']
        );

        // Assert
        $record = \Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid::find(DB::getPdo()->lastInsertId());
        $this->assertTrue($record->comments <> null, '***The uuid comment should be not-null***');
    }

    /**
     * Testing that the createUuid() method.
     *
     * @group Library
     * @group LibraryUuid
     * @group LibraryUuidUuid
     * @group LibraryUuidUuidCreateuuidmethod
     *
     * @return void
     */
    public function testCreateUuidMethod()
    {
        // Arrange

        // My UuidGenerator class injects Laravel's request object in the constructor.
        // Well, the request object is not instantiated properly within a unit test, so
        // do not want to run the constructor. Problem is that the CreateUuid() method injects
        // the request object. So, I need to mock the request object to test the CreateUuid method

        // create a mock request object
        $request = $this->getMockBuilder(Request::class)
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock()
        ;

        // the UuidGenerator's assignToRequest() method uses Laravel's request object.
        // I am not testing this specific method. However, I want to mock this method
        // because the request object is just a "pretend" object for this test (see above!).
        // So I'm stubbing this method to pretend that it just returns true, just so I can
        // get through to green in this test
        $uuidGenerator = $this->getMockBuilder(UuidGenerator::class)
            ->setMethods(['assignToRequest'])
            //->setConstructorArgs([$request])
            ->getMock()
        ;

        // pretend the assignToRequest() method returns true just for this test
        $uuidGenerator->expects($this->any())
            ->method('assignToRequest')
            ->will($this->returnValue(true))
        ;


        // Act
        $uuid = factory(\Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid::class)->make();

        $newUuid = $uuidGenerator->createUuid(
            $uuid['lasallesoftware_event_id'],
            $uuid['comments'],
            1
        );


        // Assert

        // Asserts for the most recent record inserted into the database table
        $record = \Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid::find(DB::getPdo()->lastInsertId());

        $this->assertTrue($record->lasallesoftware_event_id == 1,'***The uuid lasallesoftware_event_id from factory is 1***');
        $this->assertTrue($record->comments <> null,'***The uuid comment factory should not be null***');
        $this->assertTrue($record->created_by == 1,'***The uuid created_by factory is 1***');

        $this->assertTrue(is_int($record->lasallesoftware_event_id), "***The uuid lasallesoftware_event_id must be an integer***");
        $this->assertTrue(strlen($record->uuid) === 36, '***The uuid string length should be 36***');
        $this->assertTrue(is_int($record->created_by), "***The uuid created_by must be an integer***");

        // Trying out this assert statement
        $this->assertDatabaseHas('uuids', ['lasallesoftware_event_id' => $record->lasallesoftware_event_id]);
        $this->assertDatabaseHas('uuids', ['comments' => $record->comments]);
        $this->assertDatabaseHas('uuids', ['uuid' => $record->uuid]);
        $this->assertDatabaseHas('uuids', ['created_by' => $record->created_by]);
    }

    // Should test that the new request object properties (->lasallesoftware_event_id and ->uuid)work. Can't really
    // do that in a unit test, and anyways the login will fail if these properties are not set in UuidGenerator. So,
    // leaving this to Dusk & to http tests
}
