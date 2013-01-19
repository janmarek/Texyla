<?php

use Nette\Application\UI, Nette\Utils\Strings, Nette\Image;
use Nette\Application\Responses\TextResponse, Nette\Application\Responses\JsonResponse;

/**
 * Texyla presenter
 *
 * @author Jan Marek
 * @license MIT
 */
class TexylaPresenter extends BasePresenter
{
	/** @var string */
	private $baseFolderPath;

	/** @var string */
	private $baseFolderUri;

	/** @var string */
	private $tempDir;

	/** @var string */
	private $tempUri;


	/**
	 * Startup
	 */
	public function startup()
	{
		parent::startup();
		$texy = $this->getService('Texy');
		$this->baseFolderPath = $texy->imageModule->fileRoot;
		$this->baseFolderUri = $texy->imageModule->root;
		$this->tempDir = TEMP_DIR;
		$this->tempUri = $this->template->basePath . "/temp";
	}



	/**
	 * Texyla preview
	 */
	public function actionPreview()
	{
		$texy = $this->getService('Texy');
		$httpRequest = $this->context->httpRequest;
		$html = $texy->process($httpRequest->getPost("texy"));
		$this->sendResponse(new TextResponse($html));
	}


	// files plugin



	/**
	 * Send error message
	 * @param string $msg
	 */
	private function sendError($msg)
	{
		$this->sendResponse(new JsonResponse(array(
			"error" => $msg,
		), "text/plain"));
	}



	/**
	 * Get and check path to folder
	 * @param string $folder
	 */
	protected function getFolderPath($folder)
	{
		$folderPath = realpath($this->baseFolderPath . ($folder ? "/" . $folder : ""));

		if (!is_dir($folderPath) || !is_writable($folderPath) || !Strings::startsWith($folderPath, realpath($this->baseFolderPath))) {
			throw new \Nette\InvalidArgumentException;
		}

		return $folderPath;
	}



	/**
	 * File name with cached preview image in file browser
	 * @param string $path
	 * @return string
	 */
	protected function thumbnailFileName($path)
	{
		$path = realpath($path);
		return "texylapreview-" . md5($path . "|" . filemtime($path)) . ".jpg";
	}



	/**
	 * File browser - list files
	 * @param string $folder
	 */
	public function actionListFiles($folder = "")
	{
		// check rights
//		if (!Environment::getUser()->isAuthenticated()) {
//			$this->sendError("Access denied.");
//		}

		try {
			$folderPath = $this->getFolderPath($folder);
		} catch (InvalidArgumentException $e) {
			$this->sendError("Folder does not exist or is not writeable.");
		}

		// list of files
		$folders = array();
		$files = array();

		// up
		if ($folder !== "") {
			$lastPos = strrpos($folder, "/");
			$key = $lastPos === false ? "" : substr($folder, 0, $lastPos);

			$folders[] = array("type" => "up", "name" => "..", "key" => $key,);
		}

		foreach (new \DirectoryIterator($folderPath) as $fileInfo) {
			$fileName = $fileInfo->getFileName();

			// skip hidden files, . and ..
			if (Strings::startsWith($fileName, "."))
				continue;

			// filename with folder
			$key = ($folder ? $folder . "/" : "") . $fileName;

			// directory
			if ($fileInfo->isDir()) {
				$folders[] = array("type" => "folder", "name" => $fileName, "key" => $key,);

				// file
			} elseif ($fileInfo->isFile()) {

				// image
				if (@getImageSize($fileInfo->getPathName())) {
					$thumbFileName = $this->thumbnailFileName($fileInfo->getPathName());

					if (file_exists($this->tempDir . "/" . $thumbFileName)) {
						$thumbnailKey = $this->tempUri . "/" . $thumbFileName;
					} else {
						$thumbnailKey = $this->link("thumbnail", $key);
					}

					$files[] = array(
						"type" => "image",
						"name" => $fileName,
						"insertUrl" => $key,
						"description" => $fileName,
						"thumbnailKey" => $thumbnailKey,
					);

					// other file
				} else {
					$files[] = array(
						"type" => "file",
						"name" => $fileName,
						"insertUrl" => $this->baseFolderUri . ($folder ? "$folder/" : "") . $fileName,
						"description" => $fileName,
					);
				}
			}
		}

		// send response
		$this->sendResponse(new JsonResponse(array(
			"list" => array_merge($folders, $files),
		)));
	}



	/**
	 * Genarate and show preview of the image in file browser
	 * @param string $key
	 */
	public function actionThumbnail($key)
	{
		try {
			$path = $this->baseFolderPath . "/" . $key;
			$image = Image::fromFile($path)->resize(60, 40);
			$image->save($this->tempDir . "/" . $this->thumbnailFileName($path));
			@chmod($path, 0666);
			$image->send();
		} catch (Exception $e) {
			Image::fromString(Image::EMPTY_GIF)->send(Image::GIF);
		}

		$this->terminate();
	}


	/**
	 * File upload
	 */
	public function actionUpload()
	{
		$httpRequest = $this->context->httpRequest;
		
		// check user rights
//		if (!Environment::getUser()->isAllowed("files", "upload")) {
//			$this->sendError("Access denied.");
//		}

		// path
		$folder = $httpRequest->getPost("folder");

		try {
			$folderPath = $this->getFolderPath($folder);
		} catch (InvalidArgumentException $e) {
			$this->sendError("Folder does not exist or is not writeable.");
		}

		// file
		$file = $httpRequest->getFile("file");

		// check
		if ($file === null || !$file->isOk()) {
			$this->sendError("Upload error.");
		}

		// move
		$fileName = Strings::webalize($file->getName(), ".");
		$path = $folderPath . "/" . $fileName;

		if (@$file->move($path)) {
			@chmod($path, 0666);

			if ($file->isImage()) {
				$this->payload->filename = ($folder ? "$folder/" : "") . $fileName;
				$this->payload->type = "image";
			} else {
				$this->payload->filename = $this->baseFolderUri . ($folder ? "$folder/" : "") . $fileName;
				$this->payload->type = "file";
			}

			$this->sendResponse(new JsonResponse($this->payload, "text/plain"));
		} else {
			$this->sendError("Move failed.");
		}
	}



	/**
	 * Make directory
	 * @param string folder
	 * @param string new folder name
	 */
	public function actionMkDir($folder, $name)
	{
		$name = Strings::webalize($name);
		$path = $this->getFolderPath($folder) . "/" . $name;

		if (mkdir($path)) {
			$this->sendResponse(new JsonResponse(array(
				"name" => $name,
			)));
		} else {
			$this->sendError("Unable to create directory $path");
		}
	}



	/**
	 * Delete file or directory
	 * @param string folder
	 * @param string item name
	 */
	public function actionDelete($folder, $name)
	{
		$path = $this->getFolderPath($folder) . "/" . $name;

		if (!file_exists($path)) {
			$this->sendError("File does not exist.");
		}

		if (is_dir($path)) {
			if (rmdir($path)) {
				$this->sendResponse(new JsonResponse(array(
					"deleted" => true,
				)));
			} else {
				$this->sendError("Unable to delete directory.");
			}
		}

		if (is_file($path)) {
			if (unlink($path)) {
				$this->sendResponse(new JsonResponse(array(
					"deleted" => true,
				)));
			} else {
				$this->sendError("Unable to delete file.");
			}
		}
	}



	/**
	 * Rename file or directory
	 * @param string folder
	 * @param string old item name
	 * @param string new item name
	 */
	public function actionRename($folder, $oldname, $newname)
	{
		$oldpath = $this->getFolderPath($folder) . "/" . $oldname;
		$newpath = $this->getFolderPath($folder) . "/" . Strings::webalize($newname, ".");

		if (!file_exists($oldpath)) {
			$this->sendError("File does not exist.");
		}

		if (rename($oldpath, $newpath)) {
			$this->sendResponse(new JsonResponse(array(
				"deleted" => true,
			)));
		} else {
			$this->sendError("Unable to rename file.");
		}
	}

}
