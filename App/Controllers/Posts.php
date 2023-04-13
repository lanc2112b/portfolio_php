<?php

namespace App\Controllers;

use \Core\ViewJSON;
use App\Models\Post;
/** Posts */

class Posts extends \Core\Controller
{
    
    /**
     * list posts
     *
     * @return void
     */
    public function getIndexAction()
    {   
        $posts = Post::getAll();
        ViewJSON::responseJson($posts);
    }

    /**
     * addNew
     *
     * @return void
     */
    public function addNewAction()
    {
        echo "Adding new post";
    }

    public function editAction()
    {
        echo "<pre> Params:"
            .
            htmlspecialchars(print_r($this->route_params, true))
            .
            "</pre>";
    }
}