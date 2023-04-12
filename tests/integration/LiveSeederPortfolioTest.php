<?php

use PHPUnit\Framework\TestCase;
use \db\LiveSeederPortfolio;

class LiveSeederPortfolioTest extends TestCase
{
    public function testDropTable()
    {
        $seeder = new LiveSeederPortfolio;

        $dropped = $seeder->dropTable();

        $this->assertEquals(true, $dropped);
    }

    public function testCreateTable()
    {
        $seeder = new LiveSeederPortfolio;

        $created = $seeder->createPortfolioTable();

        $this->assertEquals(true, $created);
    }

    public function testTableHasColumns()
    {
        $seeder = new LiveSeederPortfolio;

        $columns = $seeder->describeTable();

        $this->assertIsArray($columns);
        $this->assertCount(10, $columns);
        $this->assertArrayHasKey('Field', $columns[0]);
        $this->assertEquals('id', $columns[0]['Field']);
        $this->assertEquals('title', $columns[1]['Field']);
        $this->assertEquals('description', $columns[2]['Field']);
        $this->assertEquals('hosted_url', $columns[3]['Field']);
        $this->assertEquals('github_url', $columns[4]['Field']);
        $this->assertEquals('image_url', $columns[5]['Field']);
        $this->assertEquals('video_url', $columns[6]['Field']);
        $this->assertEquals('created_at', $columns[7]['Field']);
        $this->assertEquals('updated_at', $columns[8]['Field']);
        $this->assertEquals('deleted_at', $columns[9]['Field']);
    }

    public function testAddPortfolioItems()
    {
        $seeder = new LiveSeederPortfolio;

        $itemsAdded = $seeder->addPortfolioItems();

        $this->assertEquals(true, $itemsAdded);
    }

    public function testGetPortfolioItems()
    {
        $seeder = new LiveSeederPortfolio;

        $items = $seeder->getPortfolioItems();

        $title1 = 'NC-News';
        $title2 = 'PlaidPal';

        $this->assertIsArray($items);
        $this->assertEquals(1, $items[0]['id']);
        $this->assertEquals(2, $items[1]['id']);
        $this->assertEquals($title1, $items[0]['title']);
        $this->assertEquals($title2, $items[1]['title']);
    }

}
