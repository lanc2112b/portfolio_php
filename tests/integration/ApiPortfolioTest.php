<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class ApiPortfolioTest extends TestCase
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

    public function testGetPortfolioItems()
    {

        $response = $this->client->request('GET', '/api/portfolios/index');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertCount(2, $data);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('title', $data[0]);
        $this->assertArrayHasKey('description', $data[0]);
        $this->assertArrayHasKey('hosted_url', $data[0]);
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(2, $data[1]['id']);
    }

    public function testGetPortfolioItemById()
    {

        $response = $this->client->request('GET', '/api/portfolios/1/view');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
    }

    public function testGetPortfolioItemByInvalidId()
    {

        $response = $this->client->request('GET','/api/portfolios/4/view', ['http_errors' => false]);

        $this->assertEquals(404, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertEquals('No item found', $data['item']);
    }
}
