<?php

use Nette\Environment, Nette\IComponentContainer;

/**
 * Texyla loader
 *
 * @author Jan Marek
 */
class TexylaLoader extends WebLoader\JavaScriptLoader
{
	/**
	 * Construct
	 * @param IComponentContainer parent
	 * @param string name
	 */
	public function __construct(IComponentContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		$this->tempUri = Environment::getVariable("baseUri") . "webtemp";
		$this->tempPath = WWW_DIR . "/webtemp";
		$this->sourcePath = APP_DIR . "/../../../texyla";

		$this->addFiles(array(
			// core
			"js/texyla.js",
			"js/selection.js",
			"js/texy.js",
			"js/buttons.js",
			"js/dom.js",
			"js/view.js",
			"js/ajaxupload.js",
			"js/window.js",

			// languages
			"languages/cs.js",
			"languages/sk.js",
			"languages/en.js",

			// plugins
			"plugins/keys/keys.js",
			"plugins/resizableTextarea/resizableTextarea.js",
			"plugins/img/img.js",
			"plugins/table/table.js",
			"plugins/link/link.js",
			"plugins/emoticon/emoticon.js",
			"plugins/symbol/symbol.js",
			"plugins/files/files.js",
			"plugins/color/color.js",
			"plugins/textTransform/textTransform.js",
			"plugins/youtube/youtube.js",
			"plugins/gravatar/gravatar.js",
		));

		$this->filters[] = "JSMin::minify";
	}



	/**
	 * Generated filename
	 * @param array $files
	 * @return string
	 */
	public function getGeneratedFilename(array $files = null)
	{
		if (count($files) > 0) {
			return "texyla.js";
		} else {
			return basename($files[0]);
		}
	}

}