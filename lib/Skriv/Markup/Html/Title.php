<?php

namespace Skriv\Markup\Html;

/** Gestion des titres. */
class Title extends \WikiRenderer\Block {
	public $type = 'title';
	protected $regexp = "/^(={1,6})(.*)\s*$/";
	protected $_closeNow = true;

	public function getRenderedLine() {
		$equals = $this->_detectMatch[1];
		$text = trim($this->_detectMatch[2]);
		$text = rtrim(rtrim($text, '='));
		$level = strlen($equals);

		$html = $this->_renderInlineTag($text);
		return "<h$level>$html</h$level>";
	}
}

