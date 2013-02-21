<?php

namespace Skriv\Markup\DocBook;

/**
 * traite les signes de types pre (pour afficher du code..)
 */
class Pre extends \WikiRenderer\Block {
	public $type = 'pre';
	protected $regexp = "/^\s(.*)/";
	protected $_openTag = '<literallayout>';
	protected $_closeTag = '</literallayout>';

	public function getRenderedLine() {
		$text = $this->_detectMatch[1];
		return $this->_renderInlineTag($text);
	}
}

