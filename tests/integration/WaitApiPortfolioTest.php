<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

use function PHPUnit\Framework\assertArrayHasKey;

class WaitApiPortfolioTest extends TestCase
{

    protected $client;

    protected $post_data = [
        'title' => 'A valid length title',
        'description' => 'A valid length description, above a given length',
        'hosted_url' => 'https://new.muninn.co.uk',
        'github_url' => 'https://github.com/lanc2112b/nc-news-fe',
        'image_url' => 'https://news.muninn.co.uk/carousel_imgs/react_slide.png',
        'video_url' => 'https://youtu.be/KYFwcIRx16g'
    ];

    protected $patch_data = [
        'title' => 'A Much Better Title Than the Last',
        'description' => 'A better description, of something much better, and a corrected URL for the hosted site.',
        'hosted_url' => 'https://news.muninn.co.uk',
        'github_url' => 'https://github.com/lanc2112b/nc-news-fe',
        'image_url' => 'https://news.muninn.co.uk/carousel_imgs/node_slide.png',
        'video_url' => 'https://youtu.be/KYFwcIRx16g'
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
        $this->assertEquals('No item found', $data['msg']);
    }

    public function testPostPortfolioItem()
    {

        $response = $this->client->request('POST', '/api/admin/portfolios/add',
            ['body' => json_encode($this->post_data)]);

        $this->assertEquals(201, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertArrayHasKey('item', $data);
        $this->assertCount(10, $data['item']);
        $this->assertArrayHasKey('title', $data['item']);
        $this->assertEquals('A valid length title', $data['item']['title']);
        $this->assertEquals('A valid length description, above a given length', $data['item']['description']);
        $this->assertEquals('https://new.muninn.co.uk', $data['item']['hosted_url']);
        $this->assertEquals('https://github.com/lanc2112b/nc-news-fe', $data['item']['github_url']);
        $this->assertEquals('https://news.muninn.co.uk/carousel_imgs/react_slide.png', $data['item']['image_url']);
        $this->assertEquals('https://youtu.be/KYFwcIRx16g', $data['item']['video_url']);
    }

    public function testPostBadPortfolioItem()
    {

        $response = $this->client->request('POST', '/api/admin/portfolios/add',['http_errors' => false],['body' => json_encode(['title' => 'sdfsdf' ])],
            
        );

        $this->assertEquals(400, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertEquals('Bad request', $data['msg']);
    }

    public function testPatchPortfolioItem()
    {

        $response = $this->client->request(
            'PATCH',
            '/api/admin/portfolios/3/update',
            ['body' => json_encode($this->patch_data)]
        );

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testPatchedPortfolioItem()
    {

        $response = $this->client->request(
            'GET',
            '/api/portfolios/3/view'
        );

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertArrayHasKey('item', $data);
        $this->assertCount(10, $data['item']);
        $this->assertArrayHasKey('title', $data['item']);
        $this->assertEquals('A Much Better Title Than the Last', $data['item']['title']);
        $this->assertEquals('A better description, of something much better, and a corrected URL for the hosted site.', $data['item']['description']);
        $this->assertEquals('https://news.muninn.co.uk', $data['item']['hosted_url']);
        $this->assertEquals('https://github.com/lanc2112b/nc-news-fe', $data['item']['github_url']);
        $this->assertEquals('https://news.muninn.co.uk/carousel_imgs/node_slide.png', $data['item']['image_url']);
        $this->assertEquals('https://youtu.be/KYFwcIRx16g', $data['item']['video_url']);
    }

    public function testDeletePortfolioItem()
    {

        $response = $this->client->request(
            'DELETE',
            '/api/admin/portfolios/3/delete',
            ['body' => json_encode($this->patch_data)]
        );

        $this->assertEquals(204, $response->getStatusCode());
    }
}
