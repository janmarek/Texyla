<?php
class TexyHandlers {
	/**
	 * User handler for images
	 *
	 * @param TexyHandlerInvocation  handler invocation
	 * @param TexyImage
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public static function youtubeHandler($invocation, $image, $link) {
		$parts = explode(':', $image->URL);
		if (count($parts) !== 2) return $invocation->proceed();

		switch ($parts[0]) {
		case 'youtube':
			$video = htmlSpecialChars($parts[1]);
			$dimensions = 'width="'.($image->width ? $image->width : 425).'" height="'.($image->height ? $image->height : 350).'"';
			$code = '<div><object '.$dimensions.'>'
				. '<param name="movie" value="http://www.youtube.com/v/'.$video.'" /><param name="wmode" value="transparent" />'
				. '<embed src="http://www.youtube.com/v/'.$video.'" type="application/x-shockwave-flash" wmode="transparent" '.$dimensions.' /></object></div>';

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
	public static function flashHandler($invocation, $image, $link) {
		$texy = $invocation->getTexy();

		if (substr($image->URL, -4) === '.swf') {  // accepts only *.swf
			$movie = Texy::prependRoot($image->URL, $texy->imageModule->root);

			$dimensions =
				   ($image->width ? 'width="'.$image->width.'" ' : '')
				. ($image->height ? 'width="'.$image->height.'" ' : '');

			$movie = htmlSpecialChars($movie);
			$altContent = htmlSpecialChars($image->modifier->title);

			// @see http://phpfashion.com/how-to-correctly-insert-a-flash-into-xhtml
			$code = '
	<!--[if !IE]> -->
	<object type="application/x-shockwave-flash" data="'.$movie.'" '.$dimensions.'>
	<!-- <![endif]-->

	<!--[if IE]>
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" '.$dimensions.'
	codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0">
	<param name="movie" value="'.$movie.'" />
	<!--><!--dgx-->

		<p>'.$altContent.'</p>
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
	public static function streamHandler($invocation, $image, $link) {
		$parts = explode(':', $image->URL);

		if (count($parts) != 2 || $parts[0] != "stream") {
			return $invocation->proceed();
        }

		$url = 'http://www.stream.cz/object/' . htmlSpecialChars($parts[1]);
		$width = $image->width ? $image->width : 450;
		$height = $image->height ? $image->height : 354;
		$dimensions = 'width="' . $width . '" height="' . $height . '"';

		$code = '<object ' . $dimensions . '>' .
		'<param name="movie" value="' . $url . '">' .
		'<param name="allowfullscreen" value="true">'.
		'<param name="allowscriptaccess" value="always">' .
		'<param name="wmode" value="transparent">' .
		'<embed src="' . $url . '" type="application/x-shockwave-flash"' .
		'wmode="transparent" allowfullscreen="true" '.
		'allowscriptaccess="always" ' . $dimensions . '></object>';

		$texy = $invocation->getTexy();
		return $texy->protect($code, Texy::CONTENT_BLOCK);
	}

	/**
	 * User handler for code block
	 *
	 * @param TexyHandlerInvocation  handler invocation
	 * @param string  block type
	 * @param string  text to highlight
	 * @param string  language
	 * @param TexyModifier modifier
	 * @return TexyHtml
	 */
	public static function fshlHandler($invocation, $blocktype, $content, $lang, $modifier) {
		if ($blocktype !== 'block/code') {
			return $invocation->proceed();
		}

		$lang = strtoupper($lang);
		if ($lang == 'JAVASCRIPT') $lang = 'JS';

		$fshl = new fshlParser('HTML_UTF8', P_TAB_INDENT);
		if (!$fshl->isLanguage($lang)) {
			return $invocation->proceed();
		}

		$texy = $invocation->getTexy();
		$content = Texy::outdent($content);
		$content = $fshl->highlightString($lang, $content);
		$content = $texy->protect($content, Texy::CONTENT_BLOCK);

		$elPre = TexyHtml::el('pre');
		if ($modifier) $modifier->decorate($texy, $elPre);
		$elPre->attrs['class'] = strtolower($lang);

		$elCode = $elPre->create('code', $content);

		return $elPre;
	}
	
	/**
	 * @param TexyHandlerInvocation  handler invocation
	 * @param string
	 * @param string
	 * @param TexyModifier
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public static function addTargetHandler($invocation, $phrase, $content, $modifier, $link) {
		// vychozí zpracování Texy
		$el = $invocation->proceed();

		// ověř, že $el je objekt TexyHtml a že jde o element 'a'
		if ($el instanceof TexyHtml && $el->getName() === 'a') {
			// uprav jej
			$el->attrs['target'] = '_blank';
		}

		return $el;
	}
}