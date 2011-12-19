<?php

require_once __DIR__ . '/../libs/texy.min.php';

/**
 * Texyla pro administrační rozhranní
 */
class AdminTexy extends Texy
{
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
		$this->imageModule->fileRoot = __DIR__ . "/images";
		$this->imageModule->root = "images/";

		// přidávání youtube.com, stream.cz videa, flash a gravatar
		$this->addHandler('image', array(__CLASS__, 'youtubeHandler'));
		$this->addHandler('image', array(__CLASS__, 'streamHandler'));
		$this->addHandler('image', array(__CLASS__, 'flashHandler'));
		$this->addHandler('image', array(__CLASS__, 'gravatarHandler'));
	}


	/**
	 * User handler for images
	 *
	 * @param TexyHandlerInvocation  handler invocation
	 * @param TexyImage
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public static function youtubeHandler($invocation, $image, $link)
	{
		$parts = explode(':', $image->URL);
		if (count($parts) !== 2)
			return $invocation->proceed();

		switch ($parts[0]) {
			case 'youtube':
				$video = htmlSpecialChars($parts[1]);
				$dimensions = 'width="' . ($image->width ? $image->width : 425) . '" height="' . ($image->height ? $image->height : 350) . '"';
				$code = '<div><object ' . $dimensions . '>'
					. '<param name="movie" value="http://www.youtube.com/v/' . $video . '" /><param name="wmode" value="transparent" />'
					. '<embed src="http://www.youtube.com/v/' . $video . '" type="application/x-shockwave-flash" wmode="transparent" ' . $dimensions . ' /></object></div>';

				$texy = $invocation->getTexy();
				return $texy->protect($code, Texy::CONTENT_BLOCK);
		}

		return $invocation->proceed();
	}



	/**
	 * User handler for images
	 *
	 * @param TexyHandlerInvocation  handler invocation
	 * @param TexyImage
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public static function flashHandler($invocation, $image, $link)
	{
		$texy = $invocation->getTexy();

		if (substr($image->URL, -4) === '.swf') {  // accepts only *.swf
			$movie = Texy::prependRoot($image->URL, $texy->imageModule->root);

			$dimensions =
				($image->width ? ' width="' . $image->width . '"' : '')
				. ($image->height ? ' height="' . $image->height . '"' : '');

			$movie = htmlSpecialChars($movie);
			$altContent = $image->modifier->title ? "<p>" . htmlSpecialChars($image->modifier->title) . "</p>" : "";

			// @see http://phpfashion.com/how-to-correctly-insert-a-flash-into-xhtml
			$code = '
	<!--[if !IE]> -->
	<object type="application/x-shockwave-flash" data="' . $movie . '"' . $dimensions . '>
	<!-- <![endif]-->

	<!--[if IE]>
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ' . $dimensions . '
	codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0">
	<param name="movie" value="' . $movie . '" />
	<!--><!--dgx-->

	' . $altContent . '
	</object>
	<!-- <![endif]-->
	';
			return $texy->protect($code, Texy::CONTENT_BLOCK);
		}

		return $invocation->proceed();
	}



	/**
	 * User handler for images
	 *
	 * @param TexyHandlerInvocation  handler invocation
	 * @param TexyImage
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public static function streamHandler($invocation, $image, $link)
	{
		$parts = explode(':', $image->URL, 2);

		if (count($parts) != 2 || $parts[0] != "stream") {
			return $invocation->proceed();
		}

		$url = 'http://www.stream.cz/object/' . htmlSpecialChars($parts[1]);
		$width = $image->width ? $image->width : 450;
		$height = $image->height ? $image->height : 354;
		$dimensions = 'width="' . $width . '" height="' . $height . '"';

		$code = '<object ' . $dimensions . '>' .
			'<param name="movie" value="' . $url . '">' .
			'<param name="allowfullscreen" value="true">' .
			'<param name="allowscriptaccess" value="always">' .
			'<param name="wmode" value="transparent">' .
			'<embed src="' . $url . '" type="application/x-shockwave-flash"' .
			'wmode="transparent" allowfullscreen="true" ' .
			'allowscriptaccess="always" ' . $dimensions . '></object>';

		$texy = $invocation->getTexy();
		return $texy->protect($code, Texy::CONTENT_BLOCK);
	}


	/**
	 * User handler for images
	 *
	 * @param TexyHandlerInvocation  handler invocation
	 * @param TexyImage
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public static function gravatarHandler($invocation, $image, $link)
	{
		$parts = explode(':', $image->URL);
		if (count($parts) !== 2)
			return $invocation->proceed();

		switch ($parts[0]) {
			case 'gravatar':
				$email = htmlSpecialChars($parts[1]);
				$dimensions = 'width="' . ($image->width ? $image->width : 32) . '" height="' . ($image->width ? $image->width : 32) . '"';
				$code = '<div><img ' . $dimensions . ' src="http://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?d=mm&s=32" alt="" /></div>';

				$texy = $invocation->getTexy();
				return $texy->protect($code, Texy::CONTENT_BLOCK);
		}

		return $invocation->proceed();
	}

}