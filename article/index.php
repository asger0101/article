<?php 

$url = $_GET['url'];
$url = rtrim($url, "/");
$url = explode('/', $url);

require 'libs/routes.php';

$controller_path = 'Controller_' . $url[0];
require 'controllers/' . $controller_path . '.php';
$controller = new $controller_path;

$controller->{$call_method[$url[0]]}();
