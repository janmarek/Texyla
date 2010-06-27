<?php

use Nette\Image;

require_once dirname(__FILE__) . '/../../libs/nette.php';
require_once dirname(__FILE__) . '/paths.php';

try {
	$src = get_magic_quotes_gpc() ? stripslashes($_POST["image"]) : $_POST["image"];
   	$image = Image::fromFile(FILES_BASE_PATH . "/" . $src);
	$image->resize(60, 40);
	$image->send();

} catch (Exception $e) {
	header('Content-Type: image/gif');
	echo Image::EMPTY_GIF;
}