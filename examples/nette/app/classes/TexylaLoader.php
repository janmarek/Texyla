<?php
/**
 * Texyla loader
 *
 * @author Jan Marek
 */
class TexylaLoader extends WebLoader\Nette\JavaScriptLoader
{
	/** @var string */
	private $tempUri;
	/**
	
	
	 * Construct
	 * @param IContainer parent
	 * @param string name
	 */
	public function __construct($filter, $tempUri) {
		
		$files = new \WebLoader\FileCollection(WWW_DIR . "/js/");
		$files->addFiles(array(
			// core
			"texyla/js/texyla.js",
			"texyla/js/selection.js",
			"texyla/js/texy.js",
			"texyla/js/buttons.js",
			"texyla/js/dom.js",
			"texyla/js/view.js",
			"texyla/js/ajaxupload.js",
			"texyla/js/window.js",

			// languages
			"texyla/languages/cs.js",
			"texyla/languages/sk.js",
			"texyla/languages/en.js",

			// plugins
			"texyla/plugins/keys/keys.js",
			"texyla/plugins/resizableTextarea/resizableTextarea.js",
			"texyla/plugins/img/img.js",
			"texyla/plugins/table/table.js",
			"texyla/plugins/link/link.js",
			"texyla/plugins/emoticon/emoticon.js",
			"texyla/plugins/symbol/symbol.js",
			"texyla/plugins/files/files.js",
			"texyla/plugins/color/color.js",
			"texyla/plugins/textTransform/textTransform.js",
			"texyla/plugins/youtube/youtube.js",
			"texyla/plugins/gravatar/gravatar.js",
			
			"js/texyla-init.js",
		));

	    $compiler = \WebLoader\Compiler::createJsCompiler($files, WWW_DIR."/webtemp");

		// setup filter
		$compiler->addFilter($filter);

		// minifying JS
		$compiler->addFilter("JSMin::minify");

		parent::__construct($compiler, $tempUri);
	}

}