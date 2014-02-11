<?php

namespace Services;

class CssMin
{
	/**
	 * Minify target code
	 * @param string $code
	 * @return string
	 */
	public function __invoke($code)
	{
		return \CssMin::minify($code, array("remove-last-semicolon"));
	}
}
