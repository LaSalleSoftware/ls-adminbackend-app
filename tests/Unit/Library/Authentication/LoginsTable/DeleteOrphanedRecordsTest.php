<?php

namespace Tests\Unit\Library\Authentication\LoginsTable;

// LaSalle Software classes
use Lasallesoftware\Library\Authentication\Models\Login;

// Laravel classes
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

// Third party classes
use Carbon\CarbonImmutable;


class DeleteOrphanedRecordsTest extends TestCase
{
    // Define hooks to migrate the database before and after each test
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('lslibrary:customseed');
    }

    /**
     * Test that a new login record is inserted into the database.
     *
     * Assuming that the config value of lasallesoftware-library.lasalle_number_of_minutes_allowed_before_deleting_the_logins_record
     * is its default value, which is 10 minutes.
     *
     * @group authentication
     * @group authenticationLoginstable
     * @group authenticationLoginstableDeleteorphanedrecords
     *
     * @return void
     */
    public function testDeleteOrphanedRecords()
    {
        echo "\n**Now testing Tests\Unit\Library\Authentication\LoginsTable\DeleteOrphanedRecordsTest**";


        // Arrange
        $now = CarbonImmutable::now('America/New_York');

        DB::table('logins')->insert([
            'personbydomain_id' => 1,
            'token'             => 'token1',
            'uuid'              => 'uuid1',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);

        DB::table('logins')->insert([
            'personbydomain_id' => 1,
            'token'             => 'token2',
            'uuid'              => 'uuid2',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now,
            'updated_by'        => 1,
        ]);

        DB::table('logins')->insert([
            'personbydomain_id' => 1,
            'token'             => 'token3',
            'uuid'              => 'uuid3',
            'created_at'        => $now,
            'created_by'        => 1,
            'updated_at'        => $now->subMinutes(60),
            'updated_by'        => 1,
        ]);

        // double-check that the records exist
        $this->assertDatabaseHas('logins', ['id' => '1']);
        $this->assertDatabaseHas('logins', ['id' => '2']);
        $this->assertDatabaseHas('logins', ['id' => '3']);

        $logins = $this->getMockBuilder(Login::class)
            ->setMethods(null)
            //->disableOriginalConstructor()
            ->getMock()
        ;


        // Act
        $logins->deleteInactiveLoginsRecords();

        // Assert
        $this->assertDatabaseHas('logins', ['id' => '1']);
        $this->assertDatabaseHas('logins', ['id' => '2']);
        $this->assertDatabaseMissing('logins', ['id' => '3']);
    }
}
