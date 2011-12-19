<?php

require_once __DIR__ . '/../libs/texy.min.php';

/**
 * Texyla nakonfigurovaná pro forum
 */
class ForumTexy extends Texy
{
	public function __construct()
	{
		parent::__construct();

		// output
		$this->setOutputMode(self::HTML4_TRANSITIONAL);
		$this->htmlOutputModule->removeOptional = false;
		self::$advertisingNotice = false;

		// safe mode
		TexyConfigurator::safeMode($this);

		$this->allowed['heading/surrounded'] = false;
		$this->allowed['heading/underlined'] = false;
		$this->allowed['link/definition'] = false;
		$this->allowed['image/definition'] = false;

		// spojování textu v odstavcích po enteru
		$this->mergeLines = true;

		// přidání target="_blank" k odkazům
		// $this->addHandler('phrase', array(__CLASS__, 'addTargetHandler'));
	}



	/**
	 * @param TexyHandlerInvocation  handler invocation
	 * @param string
	 * @param string
	 * @param TexyModifier
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public static function addTargetHandler($invocation, $phrase, $content, $modifier, $link)
	{
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