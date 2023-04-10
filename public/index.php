<?php 

require '../Core/Router.php';

$router = new Router();


$router->add('',['controller' => 'Home', 'action' => 'index']);
$router->add('posts', ['controller' => 'Posts', 'action' => 'index']);
$router->add('posts/new', ['controller' => 'Posts', 'action' => 'new']);


echo '<pre>';
var_dump($router->getRoutes());
echo '</pre>';


//echo get_class($router);
//echo 'Requested URL: "' . $_SERVER['QUERY_STRING'] . '"'; 
//echo "Hello";