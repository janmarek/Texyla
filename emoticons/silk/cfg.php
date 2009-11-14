<?php
// smajlíky silk - konfigurák

// kde hledat smajlíky
// cesta pro html
$this->emoticonModule->root = 'emoticons/silk/';
// cesta pro php (kvůli rozměrům souborů)
$this->emoticonModule->fileRoot = dirname(__FILE__);

// ikony
$this->emoticonModule->icons = array(
	':-)' => 'smile.png',
	':-(' => 'unhappy.png',
	';-)' => 'wink.png',
	':-D' => 'grin.png',
	':-O' => 'surprised.png',
	':-P' => 'tongue.png'
);