<?php

/**
 * example configuration for silk emoticons
 */

$this->allowed['emoticon'] = true;

$this->emoticonModule->root = '/emoticons/silk/';
$this->emoticonModule->fileRoot = dirname(__FILE__);
$this->emoticonModule->icons = array(
	':-)' => 'smile.png',
	':-(' => 'unhappy.png',
	';-)' => 'wink.png',
	':-D' => 'grin.png',
	':-O' => 'surprised.png',
	':-P' => 'tongue.png'
);