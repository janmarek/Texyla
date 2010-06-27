<?php

/**
 * example configuration for texy emoticons
 */

$this->allowed['emoticon'] = true;

$this->emoticonModule->root = '/emoticons/texy/';
$this->emoticonModule->fileRoot = dirname(__FILE__);
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