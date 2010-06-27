<?php

use Nette\Web\HttpRequest, Nette\String;

require_once dirname(__FILE__) . "/../../libs/nette.php";
require_once dirname(__FILE__) . '/paths.php';

$httpRequest = new HttpRequest;
$file = $httpRequest->getFile("file");
$folder = $httpRequest->getPost("folder", "");

$state = array();

$root = realpath(FILES_BASE_PATH);
$dir = realpath(FILES_BASE_PATH . "/" . $folder);

// security check
//if (!$allowed) {
//	$state["error"] = "You are not allowed to upload files.";
//}

if (empty($file)) {
	$state["error"] = "No file was uploaded";
}

// check directory
if ($root === false || $dir === false || !String::startsWith($dir, $root) || !is_dir($dir) || !is_writable($dir)) {
	$state["error"] = "Problem with directory to upload.";
}

if (!empty($state["error"])) {
	die(json_encode($state));
}

if ($file->isOk()) {
	$filename = String::webalize($file->getName(), ".");
	$success = @$file->move("$dir/$filename");

	if ($success) {
		// nastavit typ - je to obrázek?
		$type = @$file->getImageSize() ? "image" : "file";
		$prefix = $type == "image" ? FILES_IMAGE_INCLUDE_PREFIX : FILES_FILE_INCLUDE_PREFIX;

		$state["filename"] = $prefix . ($folder ? "$folder/" : "") . $filename;
		$state["type"] = $type;
	} else {
		$state["error"] = "Move failed";
	}

} else {
	$state["error"] = "Upload error " . $file->getError();
}

// výstup
echo json_encode($state);
