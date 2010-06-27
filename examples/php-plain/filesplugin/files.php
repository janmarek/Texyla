<?php

use Nette\String, Nette\Web\HttpRequest;

require_once dirname(__FILE__) . "/../../libs/nette.php";
require_once dirname(__FILE__) . '/paths.php';

$httpRequest = new HttpRequest;
$folder = $httpRequest->getQuery("folder", "");

$root = realpath(FILES_BASE_PATH);
$dir = realpath($root . "/" . $folder);

// check directory
if ($root === false || $dir === false || !String::startsWith($dir, $root) || !is_dir($dir)) {
	echo json_encode(array(
		"error" => "Problem with directory."
	));
	exit;
}

$directories = array();
$files = array();

// up
if ($root !== $dir) {
	$dirPieces = explode("/", $folder);
	array_pop($dirPieces);

	$directories[] = array(
		"type" => "up",
		"name" => "..",
		"key" => implode("/", $dirPieces)
	);
}

foreach (new DirectoryIterator($dir) as $fileInfo) {
	$filename = $fileInfo->getFileName();

	// skip hidden files, . and ..
	if (String::startsWith($filename, ".")) continue;

	if ($fileInfo->isDir()) {
		$directories[] = array(
			"type" => "folder",
			"name" => $filename,
			"key" => ($folder ? "$folder/" : "") . $filename,
		);

	} else {
		$isImage = @getImageSize($fileInfo->getPathName()) ? true : false;

		if ($isImage) {
			$files[] = array(
				"type" => "image",
				"name" => $filename,
				"insertUrl" => FILES_IMAGE_INCLUDE_PREFIX . ($folder ? "$folder/" : "") . $filename,
				"description" => "",
				"thumbnailKey" => ($folder ? "$folder/" : "") . $filename,
			);
		} else {
			$files[] = array(
				"type" => "file",
				"name" => $filename,
				"insertUrl" => FILES_FILE_INCLUDE_PREFIX . ($folder ? "$folder/" : "") . $filename,
				"description" => "",
			);
		}
	}
}

echo json_encode(array(
	"list" => array_merge($directories, $files)
));