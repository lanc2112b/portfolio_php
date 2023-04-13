<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

use function PHPUnit\Framework\assertArrayHasKey;

class WaitApiLandingTest extends TestCase
{

    protected $client;

    protected $post_data = [
        'area_title' => 'main',
        'area_content_title' => 'Some main page title',
        'area_content' => 'Lots of content',
        'area_content_image' => 'https://news.muninn.co.uk/carousel_imgs/react_slide.png',
    ];

    protected $patch_data = [
        'area_title' => 'main',
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
}