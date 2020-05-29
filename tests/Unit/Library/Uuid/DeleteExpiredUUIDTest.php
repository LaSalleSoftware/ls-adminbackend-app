<?php

namespace Tests\Unit\Library\Uuid;

// LaSalle Software classes
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

// Third party classes
use Carbon\CarbonImmutable;


class DeleteExpiredUUIDTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrary:customseed');
    }

    /**
     * Test that expired UUID's are deleted from the UUIDS database table 
     *
     * @group Library
     * @group LibraryUuid
     * @group LibraryUuidDeleteexpireduuid
     * @group LibraryUuidDeleteexpireduuidIsdeletedsuccessful
     *
     * @return void
     */
    public function testIsDeletedSuccessful()
    {
        echo "\n**Now testing Tests\Unit\Library\Uuidt\DeleteExpiredUUIDTest**";

        // Arrange
        config(['lasallesoftware-library.uuid_number_of_days_until_expiration' => 7]);

        $now = CarbonImmutable::now('America/New_York');

        factory(\Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid::class, 50)->create([
            'created_at' =>  $now->subDays(3),
        ]);

        factory(\Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid::class, 100)->create([
            'created_at' =>  $now->subDays(8),
        ]);

        $countBeforeDeletion = DB::table('uuids')->count();


        // Act
        $uuid = new Uuid;
        $result = $uuid->deleteExpired();

        $countAfterDeletion = DB::table('uuids')->count();


        // Assert
        $this->assertEquals(151, $countBeforeDeletion);
        $this->assertEquals(51,  $countAfterDeletion);
    }

    /**
     * Test that expired UUID's are deleted from the UUIDS database table via the artisan command.
     *
     * @group Library
     * @group LibraryUuid
     * @group LibraryUuidDeleteexpireduuid
     * @group LibraryUuidDeleteexpireduuidIsdeletedsuccessfulusingartisancommand
     *
     * @return void
     */
    public function testIsDeletedSuccessfullyUsingArtisanCommand()
    {
        // Arrange
        config(['lasallesoftware-library.uuid_number_of_days_until_expiration' => 7]);

        $now = CarbonImmutable::now('America/New_York');

        factory(\Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid::class, 50)->create([
            'created_at' =>  $now->subDays(3),
        ]);

        factory(\Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid::class, 100)->create([
            'created_at' =>  $now->subDays(8),
        ]);

        $countBeforeDeletion = DB::table('uuids')->count();


        // Act
        $this->artisan('lslibrary:deleteexpireduuid');

        $countAfterDeletion = DB::table('uuids')->count();


        // Assert
        $this->assertEquals(151, $countBeforeDeletion);
        $this->assertEquals(51,  $countAfterDeletion);
    }
}
