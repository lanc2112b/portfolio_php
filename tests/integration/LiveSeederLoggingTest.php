<?php

use PHPUnit\Framework\TestCase;
use \db\LiveSeederLogging;


class LiveSeederLoggingTest extends TestCase
{

    public function testDropTable()
    {
        $seeder = new LiveSeederLogging;

        $dropped = $seeder->dropTable();

        $this->assertEquals(true, $dropped);
    }

    public function testCreateTable()
    {
        $seeder = new LiveSeederLogging;

        $created = $seeder->createLoggingTable();

        $this->assertEquals(true, $created);
    }

    public function testTableHasColumns()
    {
        $seeder = new LiveSeederLogging;

        $columns = $seeder->describeTable();

        $this->assertIsArray($columns);
        $this->assertCount(9, $columns);
        $this->assertArrayHasKey('Field', $columns[0]);
        $this->assertEquals('id', $columns[0]['Field']);
        $this->assertEquals('created_at', $columns[1]['Field']);
        $this->assertEquals('addr', $columns[2]['Field']);
        $this->assertEquals('host', $columns[3]['Field']);
        $this->assertEquals('contr', $columns[4]['Field']);
        $this->assertEquals('action', $columns[5]['Field']);
        $this->assertEquals('params', $columns[6]['Field']);
        $this->assertEquals('username', $columns[7]['Field']);
        $this->assertEquals('validated', $columns[8]['Field']);
    }
}