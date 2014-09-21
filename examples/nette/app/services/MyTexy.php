<?php

use Nette\Application\UI;
use Nette\Utils\Strings;
use Nette\Templating\FileTemplate;
use Nette\Latte\Engine;

/**
 * My Texy
 *
 * @author Jan Marek
 * @license MIT
 * TODO validate handlers
 */
class MyTexy extends Texy
{

	/**
	 * Construct
	 */
	public function __construct(\Nette\Http\Request $request, $basePath)
	{
		parent::__construct();

		// output
		$this->setOutputMode(self::HTML5);
		$this->htmlOutputModule->removeOptional = FALSE;
		self::$advertisingNotice = FALSE;

		// headings
		$this->headingModule->balancing = TexyHeadingModule::FIXED;

		// phrases
		$this->allowed['phrase/ins'] = TRUE; // ++inserted++
		$this->allowed['phrase/del'] = TRUE; // --deleted--
		$this->allowed['phrase/sup'] = TRUE; // ^^superscript^^
		$this->allowed['phrase/sub'] = TRUE; // __subscript__
		$this->allowed['phrase/cite'] = TRUE; // ~~cite~~
		$this->allowed['deprecated/codeswitch'] = TRUE; // `=code

		// images
		$this->imageModule->fileRoot = $basePath . "/files";
		$this->imageModule->root = $request->getUrl()->getBaseUrl() . "files/";

		// flash, youtube.com, stream.cz, gravatar handlers
		$this->addHandler('image', array($this, 'youtubeHandler'));
		$this->addHandler('image', array($this, 'streamHandler'));
		$this->addHandler('image', array($this, 'flashHandler'));
		$this->addHandler("phrase", array($this, "netteLink"));
		$this->addHandler('image', array($this, 'gravatarHandler'));
	}

	/**
	 * Template factory
	 * @return FileTemplate
	 */
	private function createTemplate()
	{
		$template = new FileTemplate;
		$template->registerFilter(new Engine);
		return $template;
	}

	/**
	 * @param TexyHandlerInvocation $invocation
	 * @param string $phrase
	 * @param string $content
	 * @param TexyModifier $modifier
	 * @param TexyLink $link
	 * @return TexyHtml|string|FALSE
	 */
	public function netteLink($invocation, $phrase, $content, $modifier, $link)
	{
		// is there link?
		if (!$link) {
			return $invocation->proceed();
		}

		$url = $link->URL;

		if (Strings::startsWith($url, "plink://")) {
			$url = substr($url, 8);
			list($presenter, $params) = explode("?", $url, 2);

			$arr = array();

			if ($params) {
				parse_str($params, $arr);
			}

			$link->URL = $this->presenter->link($presenter, $arr);
		}

		return $invocation->proceed();
	}

	/**
	 * YouTube handler for images
	 *
	 * @example [* youtube:JG7I5IF6 *]
	 *
	 * @param TexyHandlerInvocation $invocation
	 * @param TexyImage $image
	 * @param TexyLink $link
	 * @return TexyHtml|string|FALSE
	 */
	public function youtubeHandler($invocation, $image, $link)
	{
		$parts = explode(':', $image->URL, 2);

		if (count($parts) !== 2 || $parts[0] !== "youtube") {
			return $invocation->proceed();
		}

		$template = $this->createTemplate()->setFile(__DIR__ . "../templates/inc/@youtube.latte");
		$template->id = $parts[1];
		if ($image->width) {
			$template->width = $image->width;
		}
		if ($image->height) {
			$template->height = $image->height;
		}

		return $this->protect((string)$template, Texy::CONTENT_BLOCK);
	}

	/**
	 * Flash handler for images
	 *
	 * @example [* flash.swf 200x150 .(alternative content) *]
	 *
	 * @param TexyHandlerInvocation $invocation
	 * @param TexyImage $image
	 * @param TexyLink $link
	 * @return TexyHtml|string|FALSE
	 */
	public function flashHandler($invocation, $image, $link)
	{
		if (!Strings::endsWith($image->URL, ".swf")) {
			return $invocation->proceed();
		}

		$template = $this->createTemplate()->setFile(__DIR__ . "../templates/inc/@flash.latte");
		$template->url = Texy::prependRoot($image->URL, $this->imageModule->root);
		$template->width = $image->width;
		$template->height = $image->height;
		if ($image->modifier->title) {
			$template->title = $image->modifier->title;
		}

		return $this->protect((string)$template, Texy::CONTENT_BLOCK);
	}

	/**
	 * User handler for images
	 *
	 * @example [* stream:98GDAS675G *]
	 *
	 * @param TexyHandlerInvocation $invocation
	 * @param TexyImage $image
	 * @param TexyLink $link
	 * @return TexyHtml|string|FALSE
	 */
	public function streamHandler($invocation, $image, $link)
	{
		$parts = explode(':', $image->URL, 2);

		if (count($parts) !== 2 || $parts[0] !== "stream") {
			return $invocation->proceed();
		}

		$template = $this->createTemplate()->setFile(__DIR__ . "../templates/inc/@stream.latte");
		$template->id = $parts[1];
		if ($image->width) {
			$template->width = $image->width;
		}
		if ($image->height) {
			$template->height = $image->height;
		}

		return $this->protect((string)$template, Texy::CONTENT_BLOCK);
	}

	/**
	 * Gravatar handler for images
	 *
	 * @example [* gravatar:user@example.com *]
	 *
	 * @param TexyHandlerInvocation $invocation
	 * @param TexyImage $image
	 * @param TexyLink $link
	 * @return TexyHtml|string|FALSE
	 */
	public function gravatarHandler($invocation, $image, $link)
	{
		$parts = explode(':', $image->URL, 2);

		if (count($parts) !== 2 || $parts[0] !== "gravatar") {
			return $invocation->proceed();
		}

		$template = $this->createTemplate()->setFile(__DIR__ . "../templates/inc/@gravatar.latte");
		$template->email = $parts[1];
		if ($image->width) {
			$template->width = $image->width;
		}
		if ($image->height) {
			$template->height = $image->height;
		}

		return $this->protect((string)$template, Texy::CONTENT_BLOCK);
	}

}
