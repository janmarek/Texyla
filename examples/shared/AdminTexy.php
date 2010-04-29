<?php
require_once dirname(__FILE__) . '/texyla.php';

/**
 * Texyla pro administrační rozhranní
 */
class AdminTexy extends Texy {
	public function __construct() {
		// volám konstruktor Texy, nutné
		parent::__construct();

		// výstup
		$this->setOutputMode(self::HTML4_TRANSITIONAL);
		$this->htmlOutputModule->removeOptional = false;
		// odstranění zprávy <!-- by Texy2! -->
		self::$advertisingNotice = false;

		// Nastavení nadpisů
		$this->headingModule->balancing = TexyHeadingModule::FIXED;

		// Povolení frází
		$this->allowed['phrase/ins'] = true;			// ++inserted++
		$this->allowed['phrase/del'] = true;			// --deleted--
		$this->allowed['phrase/sup'] = true;			// ^^superscript^^
		$this->allowed['phrase/sub'] = true;			// __subscript__
		$this->allowed['phrase/cite'] = true;			// ~~cite~~
		$this->allowed['deprecated/codeswitch'] = true;	// `=code

		// Nastavení cesty k obrázkům
		$this->imageModule->fileRoot = dirname(__FILE__) . "/../../images";
		$this->imageModule->root = "images/";

		// smajlíky
		$this->allowed['emoticon'] = true;
		// texy
		include dirname(__FILE__) . "/../emoticons/texy/cfg.php";
		// nebo silk
		// include dirname(__FILE__) . "/../emoticons/silk/cfg.php";

		// uživatelské handlery

		// přidávání youtube.com, stream.cz videa a flash
		$this->addHandler('image', array("TexyHandlers", 'youtubeHandler'));
		$this->addHandler('image', array("TexyHandlers", 'streamHandler'));
		$this->addHandler('image', array("TexyHandlers", 'flashHandler'));

		// obarvení zdrojového kódu pomocí FSHL
		$this->addHandler('block', array("TexyHandlers", 'fshlHandler'));

		// přidání target="_blank" k odkazům
		// $this->addHandler('phrase', array("TexyHandlers", 'addTargetHandler'));
    }
}