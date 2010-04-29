<?php
require_once dirname(__FILE__) . '/libs/texy.min.php';
require_once dirname(__FILE__) . '/texyhandlers.php';

class TexylaTools {
	/**
	 * Vloží soubor s poděděnou třídou Texy
	 * @param string|null cfg jméno konfigurace
	 */
	public static function requireCfg($cfg) {
		if ($cfg === null) return;

		$path = dirname(__FILE__) . "/$cfg.cfg.php";

		if (file_exists($path)) {
			require_once $path;
		}
	}

	/**
	 * Vrátí instanci nakonfigurované Texy
	 * @param string|null $cfg
	 * @return string
	 */
	public static function getTexy($cfg) {
		if ($cfg === null) return new Texy();

		self::requireCfg($cfg);

		$className = $cfg . "Texy";
		if (class_exists($className)) {
			return new $className();
		} else {
			return new Texy();
		}
	}
}

/**
 * Zpětná kompatibilita
 */

/**
 * Zpětná kompatibilita - funkce texyla()
 * @deprecated
 * @param string $text
 * @param string $cfg
 * @param string $encoding
 * @param bool $singleLine
 * @return string převedený text
 */
function texyla($text, $cfg = 'forum', $encoding = 'utf-8', $singleLine = false) {
	// konfigurace webalize
	if ($cfg == "webalize") return Texy::webalize($text);

	// normální konfigurace
	$texy = TexylaTools::getTexy($cfg);
	$texy->encoding = $encoding;
	return $texy->process($text, $singleLine);
}

/**
 * remove magic quotes gpc
 * Tato funkce slouží k odstranění ošetření způsobeného
 * direktivou magic_quotes_gpc (jedná se o zpětná lomítka přidávána
 * před uvozovky a apostrofy).
 * Byla publikována Jakubem Vránou na
 * http://php.vrana.cz/vypnuti-magic_quotes_gpc.php
 */
function removeMagicQuotesGpc() {
	if (get_magic_quotes_gpc()) {
		$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
		while (list($key, $val) = each($process)) {
			foreach ($val as $k => $v) {
				unset($process[$key][$k]);
				if (is_array($v)) {
					$process[$key][($key < 5 ? $k : stripslashes($k))] = $v;
					$process[] =& $process[$key][($key < 5 ? $k : stripslashes($k))];
				} else {
					$process[$key][stripslashes($k)] = stripslashes($v);

				}
			}
		}
	}
}