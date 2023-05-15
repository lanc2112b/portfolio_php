<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class WaitApiLandingTest extends TestCase
{

    protected $client;

    protected $post_data = [
        'area_title' => 'tertiary',
        'area_content_title' => 'Some main page title.',
        'area_content' => 'Lots of content, more than 20 chars',
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

    public function testPostLandingContent()
    {

        $response = $this->client->request(
            'POST',
            '/api/admin/landings/add',
            ['body' => json_encode($this->post_data)]
        );

        $this->assertEquals(201, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertArrayHasKey('item', $data);
        $this->assertCount(8, $data['item']);
        $this->assertArrayHasKey('area_title', $data['item']);
        $this->assertEquals('tertiary', $data['item']['area_title']);
        $this->assertEquals('Some main page title.', $data['item']['area_content_title']);
        $this->assertEquals('Lots of content, more than 20 chars', $data['item']['area_content']);
        $this->assertEquals('https://news.muninn.co.uk/carousel_imgs/react_slide.png', $data['item']['area_content_image']);
    }

    public function testPostBadLandingContent()
    {

        $response = $this->client->request(
            'POST',
            '/api/admin/landings/add',
            ['http_errors' => false],
            ['body' => json_encode(['area_title' => 'we'])],

        );

        $this->assertEquals(400, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertEquals('Bad request', $data['msg']);
    }

    public function testPatchedLandingContent()
    {

        /* var_dump($this->patch_data);
        ob_flush(); */
        $response = $this->client->request(
            'PATCH',
            '/api/admin/landings/3/update',
            ['body' => json_encode($this->patch_data)]
        );

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testDeleteLandingContent()
    {

        $response = $this->client->request(
            'DELETE',
            '/api/admin/landings/3/delete',
            ['body' => json_encode($this->patch_data)]
        );

        $this->assertEquals(204, $response->getStatusCode());
    }
}
