<?php

use PHPUnit\Framework\TestCase;
use \db\LiveSeederLanding;

class LiveSeederLandingTest extends TestCase
{
    public function testDropTable()
    {
        $seeder = new LiveSeederLanding;

        $dropped = $seeder->dropTable();

        $this->assertEquals(true, $dropped);
    }

    public function testCreateTable()
    {
        $seeder = new LiveSeederLanding;

        $created = $seeder->createLandingTable();

        $this->assertEquals(true, $created);
    }

    public function testTableHasColumns()
    {
        $seeder = new LiveSeederLanding;

        $columns = $seeder->describeTable();

        $this->assertIsArray($columns);
        $this->assertCount(8, $columns);
        $this->assertArrayHasKey('Field', $columns[0]);
        $this->assertEquals('id', $columns[0]['Field']);
        $this->assertEquals('area_title', $columns[1]['Field']);
        $this->assertEquals('area_content_title', $columns[2]['Field']);
        $this->assertEquals('area_content', $columns[3]['Field']);
        $this->assertEquals('area_content_image', $columns[4]['Field']);
        $this->assertEquals('created_at', $columns[5]['Field']);
        $this->assertEquals('updated_at', $columns[6]['Field']);
        $this->assertEquals('deleted_at', $columns[7]['Field']);

    }

}