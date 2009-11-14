<?php
require_once dirname(__FILE__) . "/../../libs/loader.php";
require_once dirname(__FILE__) . '/paths.php';

$httpRequest = new HttpRequest;
$url = $httpRequest->getQuery("image");

$path = FILES_BASE_PATH . "/" . $url;

if (!$url || !@getImageSize($path))  exit;

$image = Image::fromFile($path);
$image->resize(60, 40);
$image->send();