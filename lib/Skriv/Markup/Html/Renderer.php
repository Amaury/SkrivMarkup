<?php

namespace Skriv\Markup\Html;

/**
 * Main renderer object for processing of SkrivMarkup-enabled text.
 *
 * @author	Amaury Bouchard <amaury@amaury.net>
 * @copyright	© 2013, Amaury Bouchard
 * @package	SkrivMarkup
 * @see		WikiRenderer
 */
class Renderer extends \Skriv\Markup\Renderer {
	/**
	 * Constructor.
	 * @param	array	$params	(optional) Hash containing specific configuration parameters.
	 *		- bool		shortenLongUrl		Specifies if we must shorten URLs longer than 40 characters. (default: true)
	 *		- Closure	urlProcessFunction	URLs processing function. (default: null)
	 *		- Closure	preParseFunction	Function for pre-parse process. (default: null)
	 *		- Closure	postParseFunction	Function for post-parse process. (default: null)
	 *		- Closure	textToIdentifier	Function that converts strings into HTML dientifiers. (default: null)
	 *		- string	anchorsPrefix		Prefix of anchors' identifiers. (default: "skriv-" + random value)
	 *		- string	footnotesPrefix		Prefix of footnotes' identifiers. (default: "skriv-notes-" + random value)
	 */
	public function __construct(array $params=null) {
		$this->_config = new Config($params);
		$this->_wikiRenderer = new \WikiRenderer\Renderer($this->_config);
	}
}

