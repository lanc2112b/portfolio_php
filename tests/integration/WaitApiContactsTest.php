<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class WaitApiContactsTest extends TestCase
{

    protected $client;

    protected $post_data = [
        'name' => 'Dave Bobson',
        'email' => 'daveb@muninn.co.uk',
        'subject' => 'A new website please',
        'query' => 'How much for the design and functionality I\'ll be sending over later?',
        'source' => 'Your portfolio website'
    ];

    protected $patch_data = [
        'notified' => 1
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

    public function testGetContactItems()
    {

        $response = $this->client->request('GET', '/api/admin/contacts/index');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertCount(2, $data);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('name', $data[0]);
        $this->assertArrayHasKey('email', $data[0]);
        $this->assertArrayHasKey('subject', $data[0]);
        $this->assertArrayHasKey('query', $data[0]);
        $this->assertArrayHasKey('source', $data[0]);
        $this->assertArrayHasKey('notified', $data[0]);
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(2, $data[1]['id']);
    }

    public function testGetContactItemById()
    {

        $response = $this->client->request('GET', '/api/admin/contacts/1/view');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
    }

    public function testGetContactItemByInvalidId()
    {

        $response = $this->client->request('GET', '/api/admin/contacts/4/view', ['http_errors' => false]);

        $this->assertEquals(404, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertEquals('No item found', $data['msg']);
    }

    public function testPostContactItem()
    {

        $response = $this->client->request('POST', '/api/contacts/add',
            ['body' => json_encode($this->post_data)]);

        $this->assertEquals(201, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertArrayHasKey('item', $data);
        $this->assertCount(10, $data['item']);
        $this->assertArrayHasKey('name', $data['item']);
        $this->assertEquals('Dave Bobson', $data['item']['name']);
        $this->assertEquals('daveb@muninn.co.uk', $data['item']['email']);
        $this->assertEquals('A new website please', $data['item']['subject']);
        $this->assertEquals('How much for the design and functionality I\'ll be sending over later?', $data['item']['query']);
        $this->assertEquals('Your portfolio website', $data['item']['source']);
        $this->assertEquals(0 , $data['item']['notified']);
    }

    public function testPostBadContactItem()
    {

        $response = $this->client->request('POST', '/api/contacts/add',['http_errors' => false],['body' => json_encode(['name' => 'sdfsdf' ])],
            
        );

        $this->assertEquals(400, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertEquals('Bad request', $data['msg']);
    }

    public function testPatchContactItem()
    {

        $response = $this->client->request(
            'PATCH',
            '/api/admin/contacts/3/update',
            ['body' => json_encode($this->patch_data)]
        );

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testPatchedContactItem()
    {

        $response = $this->client->request(
            'GET',
            '/api/admin/contacts/3/view'
        );

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertArrayHasKey('item', $data);
        $this->assertCount(10, $data['item']);
        $this->assertArrayHasKey('name', $data['item']);
        $this->assertEquals('Dave Bobson', $data['item']['name']);
        $this->assertEquals('daveb@muninn.co.uk', $data['item']['email']);
        $this->assertEquals('A new website please', $data['item']['subject']);
        $this->assertEquals('How much for the design and functionality I\'ll be sending over later?', $data['item']['query']);
        $this->assertEquals('Your portfolio website', $data['item']['source']);
        $this->assertEquals(1, $data['item']['notified']);
    }

    public function testDeleteContactItem()
    {

        $response = $this->client->request(
            'DELETE',
            '/api/admin/contacts/3/delete'
        );

        $this->assertEquals(204, $response->getStatusCode());
    }
}
