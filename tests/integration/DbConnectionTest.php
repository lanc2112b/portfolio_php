<?php

use PHPUnit\Framework\TestCase;
use \db\Seeder;

class DbConnectionTest extends TestCase
{
  public function testDropTable()
  {
    $seeder = new Seeder;

    $dropped = $seeder->dropTable();

    $this->assertEquals(true, $dropped);
  }

  public function testCreateTable()
  {
    $seeder = new Seeder;

    $created = $seeder->createPostsTable();

    $this->assertEquals(true, $created);
  }

  public function testAddPosts()
  {
    $seeder = new Seeder;

    $inserted = $seeder->addPosts();

    $this->assertEquals(true, $inserted);
  }

  public function testGetPosts()
  {
    $seeder = new Seeder;

    $posts = $seeder->getPosts();

    $title1 = 'First post in DB';
    $title2 = 'Second post in DB';

    $this->assertIsArray($posts);
    $this->assertEquals(1, $posts[0]['id']);
    $this->assertEquals(2, $posts[1]['id']);
    $this->assertEquals($title1, $posts[0]['title']);
    $this->assertEquals($title2, $posts[1]['title']);
  }
}
