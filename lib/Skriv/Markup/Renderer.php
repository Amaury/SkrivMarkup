<?php

namespace Skriv\Markup;

/**
 * Main renderer object for processing of SkrivMarkup-enabled text.
 *
 * @author	Amaury Bouchard <amaury@amaury.net>
 * @copyright	© 2013, Amaury Bouchard
 * @package	SkrivMarkup
 * @see		WikiRenderer
 */
class Renderer {
	/**
	 * Factory method. Creates a renderer object of the given type.
	 * @param	string	$type	(optional) Type of rendering object. "html" by default.
	 * @param	array	$params	(optional) Hash of parameters. The accepted parameters depends of the chosen rendering type.
	 * @return	\Skriv\Markup\Renderer	A rendering object.
	 * @throws	Exception	If something goes wrong.
	 */
	static public function factory($type='html', array $params=null) {
		if (!isset($type) || !strcasecmp($type, 'html'))
			return (new Html\Renderer($params));
		throw new Exception("Unknown Skriv rendering type '$type'.");
	}
	/**
	 * Parses a Skriv text and generates a converted text.
	 * @param	string	$text	The text to parse.
	 * @return	string	The generated HTML stream.
	 * @throws	Exception	Always thrown.
	 */
	public function render($text) {
		throw new Exception("This method should never be called.");
	}
}
