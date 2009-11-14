<?php
// smajlíky texy (výchozí) - konfigurák

// kde hledat smajlíky
// cesta pro html
$this->emoticonModule->root = 'emoticons/texy/';
// cesta pro php (kvůli rozměrům souborů)
$this->emoticonModule->fileRoot = dirname(__FILE__);

// ikony
$this->emoticonModule->icons = array(
	':-)' => 'smile.gif',
	':-(' => 'sad.gif',
	';-)' => 'wink.gif',
	':-D' => 'biggrin.gif',
	'8-O' => 'eek.gif',
	'8-)' => 'cool.gif',
	':-?' => 'confused.gif',
	':-x' => 'mad.gif',
	':-P' => 'razz.gif',
	':-|' => 'neutral.gif'
);