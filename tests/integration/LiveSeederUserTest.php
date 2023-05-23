<?php

use PHPUnit\Framework\TestCase;
use \db\LiveSeederUser;

class LiveSeederUserTest extends TestCase
{
    public function testDropTable()
    {
        $seeder = new LiveSeederUser;

        $dropped = $seeder->dropTable();

        $this->assertEquals(true, $dropped);
    }

    public function testCreateTable()
    {
        $seeder = new LiveSeederUser;

        $created = $seeder->createUsersTable();

        $this->assertEquals(true, $created);
    }

    public function testTableHasColumns()
    {
        $seeder = new LiveSeederUser;

        $columns = $seeder->describeTable();

        $this->assertIsArray($columns);
        $this->assertCount(12, $columns);
        $this->assertArrayHasKey('Field', $columns[0]);
        $this->assertEquals('id', $columns[0]['Field']);
        $this->assertEquals('gid', $columns[1]['Field']);
        $this->assertEquals('is_admin', $columns[2]['Field']);
        $this->assertEquals('first_name', $columns[3]['Field']);
        $this->assertEquals('last_name', $columns[4]['Field']);
        $this->assertEquals('email', $columns[5]['Field']);
        $this->assertEquals('photo_url', $columns[6]['Field']);
        $this->assertEquals('created_at', $columns[7]['Field']);
        $this->assertEquals('updated_at', $columns[8]['Field']);
        $this->assertEquals('deleted_at', $columns[9]['Field']);
        $this->assertEquals('refresh_at', $columns[10]['Field']);
        $this->assertEquals('refresh_token', $columns[11]['Field']);
    }


}
