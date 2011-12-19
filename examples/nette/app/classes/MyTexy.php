<?php

require_once __DIR__ . "/../../../libs/texy.min.php";

use Nette\Environment, Nette\String;
use Nette\Templates\Template, Nette\Templates\LatteFilter;

/**
 * My Texy
 *
 * @author Jan Marek
 * @license MIT
 */
class MyTexy extends Texy
{
	/**
	 * Construct
	 */
	public function __construct()
	{
		parent::__construct();

		// output
		$this->setOutputMode(self::HTML4_TRANSITIONAL);
		$this->htmlOutputModule->removeOptional = false;
		self::$advertisingNotice = false;

		// headings
		$this->headingModule->balancing = TexyHeadingModule::FIXED;

		// phrases
		$this->allowed['phrase/ins'] = true;   // ++inserted++
		$this->allowed['phrase/del'] = true;   // --deleted--
		$this->allowed['phrase/sup'] = true;   // ^^superscript^^
		$this->allowed['phrase/sub'] = true;   // __subscript__
		$this->allowed['phrase/cite'] = true;   // ~~cite~~
		$this->allowed['deprecated/codeswitch'] = true; // `=code

		// images
		$this->imageModule->fileRoot = WWW_DIR . "/files";
		$this->imageModule->root = Environment::getVariable("baseUri") . "files/";

		// flash, youtube.com, stream.cz, gravatar handlers
		$this->addHandler('image', array($this, 'youtubeHandler'));
		$this->addHandler('image', array($this, 'streamHandler'));
		$this->addHandler('image', array($this, 'flashHandler'));
		$this->addHandler("phrase", array($this, "netteLink"));
		$this->addHandler('image', array($this, 'gravatarHandler'));
	}



	/**
	 * Template factory
	 * @return Template
	 */
	private function createTemplate()
	{
		$template = new FileTemplate;
		$template->registerFilter(new LatteFilter);
		return $template;
	}



	/**
	 * @param TexyHandlerInvocation  handler invocation
	 * @param string
	 * @param string
	 * @param TexyModifier
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public function netteLink($invocation, $phrase, $content, $modifier, $link)
	{
		// is there link?
		if (!$link) return $invocation->proceed();

		$url = $link->URL;

		if (String::startsWith($url, "plink://")) {
			$url = substr($url, 8);
			list($presenter, $params) = explode("?", $url, 2);

			$arr = array();

			if ($params) {
				parse_str($params, $arr);
			}

			$link->URL = Environment::getApplication()->getPresenter()->link($presenter, $arr);
		}

		return $invocation->proceed();
	}



	/**
	 * YouTube handler for images
	 *
	 * @example [* youtube:JG7I5IF6 *]
	 *
	 * @param TexyHandlerInvocation  handler invocation
	 * @param TexyImage
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public function youtubeHandler($invocation, $image, $link)
	{
		$parts = explode(':', $image->URL, 2);

		if (count($parts) !== 2 || $parts[0] !== "youtube") {
			return $invocation->proceed();
		}

		$template = $this->createTemplate()->setFile(APP_DIR . "/templates/inc/@youtube.phtml");
		$template->id = $parts[1];
		if ($image->width) $template->width = $image->width;
		if ($image->height) $template->height = $image->height;

		return $this->protect((string) $template, Texy::CONTENT_BLOCK);
	}



	/**
	 * Flash handler for images
	 *
	 * @example [* flash.swf 200x150 .(alternative content) *]
	 *
	 * @param TexyHandlerInvocation  handler invocation
	 * @param TexyImage
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public function flashHandler($invocation, $image, $link)
	{
		if (!String::endsWith($image->URL, ".swf")) {
			return $invocation->proceed();
		}

		$template = $this->createTemplate()->setFile(APP_DIR . "/templates/inc/@flash.phtml");
		$template->url = Texy::prependRoot($image->URL, $this->imageModule->root);
		$template->width = $image->width;
		$template->height = $image->height;
		if ($image->modifier->title) $template->title = $image->modifier->title;

		return $this->protect((string) $template, Texy::CONTENT_BLOCK);
	}



	/**
	 * User handler for images
	 *
	 * @example [* stream:98GDAS675G *]
	 *
	 * @param TexyHandlerInvocation  handler invocation
	 * @param TexyImage
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public function streamHandler($invocation, $image, $link)
	{
		$parts = explode(':', $image->URL, 2);

		if (count($parts) !== 2 || $parts[0] !== "stream") {
			return $invocation->proceed();
		}

		$template = $this->createTemplate()->setFile(APP_DIR . "/templates/inc/@stream.phtml");
		$template->id = $parts[1];
		if ($image->width) $template->width = $image->width;
		if ($image->height) $template->height = $image->height;

		return $this->protect((string) $template, Texy::CONTENT_BLOCK);
	}



	/**
	 * Gravatar handler for images
	 *
	 * @example [* gravatar:user@example.com *]
	 *
	 * @param TexyHandlerInvocation  handler invocation
	 * @param TexyImage
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public function gravatarHandler($invocation, $image, $link)
	{
		$parts = explode(':', $image->URL, 2);

		if (count($parts) !== 2 || $parts[0] !== "gravatar") {
			return $invocation->proceed();
		}

		$template = $this->createTemplate()->setFile(APP_DIR . "/templates/inc/@gravatar.phtml");
		$template->email = $parts[1];
		if ($image->width) $template->width = $image->width;
		if ($image->height) $template->height = $image->height;

		return $this->protect((string) $template, Texy::CONTENT_BLOCK);
	}

}