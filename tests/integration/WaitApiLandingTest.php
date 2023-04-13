<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class WaitApiLandingTest extends TestCase
{

    protected $client;

    protected $post_data = [
        'area_title' => 'tertiary',
        'area_content_title' => 'Some main page title',
        'area_content' => 'Lots of content',
        'area_content_image' => 'https://news.muninn.co.uk/carousel_imgs/react_slide.png',
    ];

    protected $patch_data = [
        'area_title' => 'tertiary',
        'area_content_title' => 'A Better Main Area Title',
        'area_content' => 'Should really put some lorem ipsum here',
        'area_content_image' => 'https://news.muninn.co.uk/carousel_imgs/node_slide.png',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client([
            'base_uri' => 'https://port.kamikazewatermelon.co.uk/',
            'verify' => false,
            'headers' => ['Content-Type' => 'application/json']
        ]);
    }

    public function testGetLandingsItems()
    {

        $response = $this->client->request('GET', '/api/landings/index');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertCount(2, $data);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('area_title', $data[0]);
        $this->assertArrayHasKey('area_content_title', $data[0]);
        $this->assertArrayHasKey('area_content', $data[0]);
        $this->assertArrayHasKey('area_content_image', $data[0]);
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(2, $data[1]['id']);
    }
}