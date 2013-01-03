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
	/** The configuration object. */
	private $_config = null;
	/** The WikiRenderer object. */
	private $_wikiRenderer = null;

	/**
	 * Constructor.
	 * @param	array	$params	(optional) Hash containing specific configuration parameters.
	 *		- bool		shortenLongUrl		Specifies if we must shorten URLs longer than 40 characters. (default: true)
	 *		- Closure	urlProcessFunction	URLs processing function. (default: null)
	 *		- Closure	preParseFunction	Function for pre-parse process. (default: null)
	 *		- Closure	postParseFunction	Function for post-parse process. (default: null)
	 *		- string	anchorsPrefix		Prefix of anchors' identifiers. (default: "skriv-" + random value)
	 *		- string	footnotesPrefix		Prefix of footnotes' identifiers. (default: "skriv-notes-" + random value)
	 *		- int		skrivElementId		Identifier of the currently processed Skriv element. (default: null)
	 *		- bool		processSkrivLinks	Specifies if we must process Skriv-specific URLs. (default: false)
	 */
	public function __construct(array $params=null) {
		$this->_config = new Config($params);
		$this->_wikiRenderer = new \WikiRenderer\Renderer($this->_config);
	}
	/**
	 * Parses a Skriv text and generates an HTML version.
	 * @param	string	$text	The text to parse.
	 * @return	string	The generated HTML stream.
	 */
	public function render($text) {
		return ($this->_wikiRenderer->render($text));
	}
}
