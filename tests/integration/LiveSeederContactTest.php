<?php

use PHPUnit\Framework\TestCase;
use \db\LiveSeederContact;

class LiveSeederContactTest extends TestCase
{
    public function testDropTable()
    {
        $seeder = new LiveSeederContact;

        $dropped = $seeder->dropTable();

        $this->assertEquals(true, $dropped);
    }

    public function testCreateTable()
    {
        $seeder = new LiveSeederContact;

        $created = $seeder->createContactTable();

        $this->assertEquals(true, $created);
    }

    public function testTableHasColumns()
    {
        $seeder = new LiveSeederContact;

        $columns = $seeder->describeTable();

        $this->assertIsArray($columns);
        $this->assertCount(10, $columns);
        $this->assertArrayHasKey('Field', $columns[0]);
        $this->assertEquals('id', $columns[0]['Field']);
        $this->assertEquals('name', $columns[1]['Field']);
        $this->assertEquals('email', $columns[2]['Field']);
        $this->assertEquals('subject', $columns[3]['Field']);
        $this->assertEquals('query', $columns[4]['Field']);
        $this->assertEquals('source', $columns[5]['Field']);
        $this->assertEquals('notified', $columns[6]['Field']);
        $this->assertEquals('created_at', $columns[7]['Field']);
        $this->assertEquals('updated_at', $columns[8]['Field']);
        $this->assertEquals('deleted_at', $columns[9]['Field']);
    }

    public function testAddContactItems()
    {
        $seeder = new LiveSeederContact;

        $itemsAdded = $seeder->addContactItems();

        $this->assertEquals(true, $itemsAdded);
    }

    public function testGetContactItems()
    {
        $seeder = new LiveSeederContact;

        $items = $seeder->getContactItems();

        $name1 = 'Bob Mortimar';
        $name2 = 'Captain Kirk';

        $this->assertIsArray($items);
        $this->assertEquals(1, $items[0]['id']);
        $this->assertEquals(2, $items[1]['id']);
        $this->assertEquals($name1, $items[0]['name']);
        $this->assertEquals($name2, $items[1]['name']);
    }
}