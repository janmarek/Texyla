<?php
require_once dirname(__FILE__) . '/texyla.php';

/**
 * Konfigurace Texyly určená pro zpracování jednořádkových vstupů
 * (nadpisy, popisky apod.), téměř všechno se zakáže.
 */
class OnelineTexy extends Texy {
	public function __construct() {
		// volám konstruktor Texy, nutné
		parent::__construct();

		// výstup
		$this->setOutputMode(Texy::HTML4_STRICT);
		$this->htmlOutputModule->removeOptional = false;
		# odstranění zprávy <!-- by Texy2! -->
		self::$advertisingNotice = false;

		# použijeme safe mod
		TexyConfigurator::safeMode($this);
		

		# zakázání referencí
		$this->allowed['link/definition'] = false;
		$this->allowed['image/definition'] = false;


		# zakázaní nadpisů
		$this->allowed['heading/surrounded'] = false;
		$this->allowed['heading/underlined'] = false;


		# zalamování textu v odstavcích po enteru
		$this->mergeLines = false;


		# fráze
		# Protože se jedná o konfigurák pro jednořádkový text nastavte si,
		# co chcete převádět a co už ne

		$this->allowed['phrase/strong+em'] = FALSE;  // ***strong+emphasis***
		$this->allowed['phrase/strong'] = FALSE;     // **strong**
		$this->allowed['phrase/em'] = FALSE;         // //emphasis//
		$this->allowed['phrase/em-alt'] = FALSE;     // *emphasis*
		$this->allowed['phrase/span'] = FALSE;       // "span"
		$this->allowed['phrase/span-alt'] = FALSE;   // ~span~
		$this->allowed['phrase/acronym'] = FALSE;    // "acro nym"((...))
		$this->allowed['phrase/acronym-alt'] = FALSE;// acronym((...))
		$this->allowed['phrase/code'] = FALSE;       // `code`
		$this->allowed['phrase/notexy'] = FALSE;     // ''....''
		$this->allowed['phrase/quote'] = FALSE;      // >>quote<<:...
		$this->allowed['phrase/quicklink'] = FALSE;  // ....:LINK
		$this->allowed['phrase/sup-alt'] = FALSE;    // superscript^2
		$this->allowed['phrase/sub-alt'] = FALSE;    // subscript_3

		$this->allowed['phrase/ins'] = FALSE;       // ++inserted++
		$this->allowed['phrase/del'] = FALSE;		// --deleted--
		$this->allowed['phrase/sup'] = FALSE;		//^^superscript^^
		$this->allowed['phrase/sub'] = FALSE;       // __subscript__
		$this->allowed['phrase/cite'] = FALSE;      // ~~cite~~
		$this->allowed['deprecated/codeswitch'] = FALSE;// `=...


		// vypnutí/zapnutí html tagů
		$this->allowedTags = FALSE;

		// povolí / zakáže převody jednotlivých druhů odkazů
		$this->allowed['link/link'] = true;
		$this->allowed['link/url'] = true;
		$this->allowed['link/email'] = true;


		// Smajlíky

		// zapnout/vypnout zpracování smailíků
		$this->allowed['emoticon'] = false;
    }

	public function process($text, $singleLine = true) {
		return parent::process($text, $singleLine);
	}
}