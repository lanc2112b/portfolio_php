<?php

use PHPUnit\Framework\TestCase;
use App\Models\Portfolio;

class PortfolioModelTest extends TestCase
{
    protected $valid = [
        'title' => 'A valid length title',
        'description' => 'A valid length description, above a given length',
        'hosted_url' => 'https://new.muninn.co.uk',
        'github_url' => 'https://github.com/lanc2112b/nc-news-fe',
        'image_url' => 'https://news.muninn.co.uk/carousel_imgs/react_slide.png',
        'video_url' => 'https://youtu.be/KYFwcIRx16g'
    ];

    protected $invalid = [
        'title' => 'An invalid',
        'description' => 'An valid length',
        'hosted_url' => 'htdftpsnew.muninn.co.uk-67',
        'github_url' => 'lanc2112b/?asdf-_nc-news-fe',
        'image_url' => 'https://news.muninn.co.uk/carousel_imgs/react_slide',
        'video_url' => 'youtube/KYFwcIRx16g'
    ];

    public function testValidateUserInput()
    {
        $build = new Portfolio($this->valid);

        $build->validate();

        $this->assertCount(0, $build->errors);
    }

    public function testInvalidateUserInput()
    {
        $build = new Portfolio($this->invalid);

        $build->validate();

        $this->assertCount(6, $build->errors);
    }

}