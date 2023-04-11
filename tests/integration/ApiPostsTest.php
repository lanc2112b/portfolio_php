<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class ApiPostsTest extends TestCase
{

    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client([
            'base_uri' => 'https://port.kamikazewatermelon.co.uk/',
            'verify' => false
        ]);
    }

    public function testGetValidResponse()
    {
        
        $response = $this->client->request('GET', '/posts/index');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertCount(2, $data);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('title', $data[0]);
        $this->assertArrayHasKey('content', $data[0]);
        $this->assertArrayHasKey('created_at', $data[0]);
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(2, $data[1]['id']);
    }
}