<?php

namespace Skriv\Markup\Html;

/**
 * traite les signes de types pre (pour afficher du code..)
 */
class Pre extends \WikiRenderer\Block {
	public $type = 'pre';
	protected $regexp = "/^\s(.*)/";
	protected $_openTag = '<pre>';
	protected $_closeTag = '</pre>';

	public function getRenderedLine() {
		$text = $this->_detectMatch[1];
		return $this->_renderInlineTag($text);
	}
}

