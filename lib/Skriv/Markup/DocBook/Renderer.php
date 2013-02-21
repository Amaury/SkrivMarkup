<?php

namespace Skriv\Markup\DocBook;

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
	 */
	public function __construct(array $params=null) {
		$this->_config = new Config($params);
		$this->_wikiRenderer = new \WikiRenderer\Renderer($this->_config);
	}
}

