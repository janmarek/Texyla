<?php
require_once dirname(__FILE__) . '/texyla.php';

/**
 * Texyla nakonfigurovaná pro forum
 */
class ForumTexy extends Texy {
	public function __construct() {
		// volám konstruktor Texy, nutné
		parent::__construct();

		// výstup
		$this->setOutputMode(self::HTML4_TRANSITIONAL);
		$this->htmlOutputModule->removeOptional = false;
		// odstranění zprávy <!-- by Texy2! -->
		self::$advertisingNotice = false;

		// safe mode
		TexyConfigurator::safeMode($this);

		// zakázaní nadpisů
		$this->allowed['heading/surrounded'] = false;
		$this->allowed['heading/underlined'] = false;

		// smajlíky
		$this->allowed['emoticon'] = true;
		// texy
		include dirname(__FILE__) . "/../emoticons/texy/cfg.php";
		// nebo silk
		// include dirname(__FILE__) . "/../emoticons/silk/cfg.php";

		// spojování textu v odstavcích po enteru
		$this->mergeLines = true;

		// zakázání referencí
		$this->allowed['link/definition'] = false;
		$this->allowed['image/definition'] = false;

		// uživatelské handlery

		// přidávání youtube.com, stream.cz videa a flash
		// $this->addHandler('image', array("TexyHandlers", 'youtubeHandler'));
		// $this->addHandler('image', array("TexyHandlers", 'streamHandler'));
		// $this->addHandler('image', array("TexyHandlers", 'flashHandler'));

		// obarvení zdrojového kódu pomocí FSHL
		$this->addHandler('block', array("TexyHandlers", 'fshlHandler'));

		// přidání target="_blank" k odkazům
		// $this->addHandler('phrase', array("TexyHandlers", 'addTargetHandler'));
    }
}