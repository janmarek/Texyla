<?php
// nette
require_once dirname(__FILE__) . "/../../libs/loader.php";

$httpRequest = new HttpRequest;
$folder = $httpRequest->getQuery("folder", "");

/** cesty *********************************************************************/

require_once dirname(__FILE__) . '/paths.php';

$root = FILES_BASE_PATH;
$dir = realpath($root . "/" . $folder);

/** kontroly ******************************************************************/

// kontrola adresáře
if ($root === false || $dir === false || !String::startsWith($dir, $root) || !is_dir($dir)) {
	$state["error"] = "Problem with directory";
}

/** vyhledání položek *********************************************************/

$directories = array();
$files = array();

// nahoru
if ($folder !== "") {
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

	// přeskočit skryté soubory, . a ..
	if (String::startsWith($filename, ".")) continue;

	// adresář
	if ($fileInfo->isDir()) {
		$directories[] = array(
			"type" => "folder",
			"name" => $filename,
			"key" => ($folder ? "$folder/" : "") . $filename,
		);

	// soubor
	} else {
		$isImage = @getImageSize($fileInfo->getPathName()) ? true : false;
		
		if ($isImage) {
			$files[] = array(
				"type" => "image",
				"name" => $filename,
				"insertUrl" => FILES_IMAGE_INCLUDE_PREFIX . ($folder ? "$folder/" : "") . $filename,
				"description" => "Image $filename",
				"thumbailKey" => ($folder ? "$folder/" : "") . $filename,
			);
		} else {
			$files[] = array(
				"type" => "file",
				"name" => $filename,
				"insertUrl" => FILES_FILE_INCLUDE_PREFIX . ($folder ? "$folder/" : "") . $filename,
				"description" => "File $filename",
			);
		}
	}
}

// output
echo json_encode(array("list" => array_merge($directories, $files)));