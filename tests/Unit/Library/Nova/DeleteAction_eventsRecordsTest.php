<?php

namespace Tests\Unit\Library\Nova;

// LaSalle Software classes
use Lasallesoftware\Librarybackend\Nova\DeleteActioneventsRecords;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Third party classes
use Carbon\CarbonImmutable;


class DeleteAction_eventsRecordsTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrarybackend:customseed');
    }

    /**
     * Test that expired UUID's are deleted from the UUIDS database table 
     *
     * @group Library
     * @group LibraryNova
     * @group LibraryNovaDeleteaction_eventsrecords
     * @group LibraryNovaDeleteaction_eventsrecordsIsdeletedsuccessful   
     *
     * @return void
     */
    public function testIsDeletedSuccessful()
    {
        echo "\n**Now testing Tests\Unit\Library\Nova\DeleteAction_eventsRecordsTest**";

        // Arrange
        config(['lasallesoftware-librarybackend.actionevents_number_of_days_until_deletion' => 7]);

        $this->insertRecord(0);
        $this->insertRecord(9);

        // Act
        $deleteactioneventsrecords = new DeleteActioneventsRecords;
        $deleteactioneventsrecords->deleteRecords();

        // Assert
        $countAfterDeletion = DB::table('action_events')->count();
        $this->assertEquals(1,  $countAfterDeletion);
    }

    /**
     * Test that expired UUID's are deleted from the UUIDS database table 
     *
     * @group Library
     * @group LibraryNova
     * @group LibraryNovaDeleteaction_eventsrecords
     * @group LibraryNovaDeleteaction_eventsrecordsIsdeletedsuccessfulusingartisancommand 
     *
     * @return void
     */
    public function testIsDeletedSuccessfullyUsingArtisanCommand()
    {
        // Arrange
        config(['lasallesoftware-librarybackend.actionevents_number_of_days_until_deletion' => 7]);

        $this->insertRecord(0);
        $this->insertRecord(9);

        // Act
        $deleteactioneventsrecords = new DeleteActioneventsRecords;
        $this->artisan('lslibrarybackend:deleteactioneventsrecords');

        // Assert
        $countAfterDeletion = DB::table('action_events')->count();
        $this->assertEquals(1,  $countAfterDeletion);
    }


    protected function insertRecord($subDays)
    {
        $faker = \Faker\Factory::create();

        $now = CarbonImmutable::now('America/New_York');

        $data = [
            'batch_id' => $faker->uuid(),
            'user_id'  => 1,
            'name'     => $faker->randomElement($array = array ('Create', 'Update', 'Delete')),
            'actionable_type' => $faker->text(),
            'actionable_id' => 1,
            'target_type' => 1,
            'target_id'  => 1,
            'model_type' => $faker->text(),
            'model_id'   => NULL,
            'fields'     => 'fields',
            'status'     => 'finished',
            'exception'  => 'exception',
            'created_at' => $now->subDays($subDays),
            'updated_at' => $now->subDays($subDays),
            'original'   => NULL,
            'changes'    => NULL,

        ];

        DB::table('action_events')->insert([
            $data,
        ]);
    }

        
}
