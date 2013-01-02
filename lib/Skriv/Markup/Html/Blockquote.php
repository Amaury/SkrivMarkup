<?php

namespace Skriv\Markup\Html;

/**
 * traite les signes de type blockquote
 */
class Blockquote extends \WikiRenderer\Block {
	public $type = 'bq';
	protected $regexp = "/^(\>+)(.*)/";

	public function open() {
		$this->_previousTag = $this->_detectMatch[1];
		$this->_firstTagLen = strlen($this->_previousTag);
		$this->_firstLine = true;
		return (str_repeat('<blockquote>', $this->_firstTagLen) . '<p>');
	}
	public function close() {
		return ('</p>' . str_repeat('</blockquote>', strlen($this->_previousTag)));
	}
	public function getRenderedLine() {
		$d = strlen($this->_previousTag) - strlen($this->_detectMatch[1]);
		$str = '';
		if ($d > 0) { // on remonte d'un cran dans la hierarchie...
			$str = '</p>' . str_repeat('</blockquote>', $d) . '<p>';
			$this->_previousTag = $this->_detectMatch[1];
		} else if ($d < 0) { // un niveau de plus
			$this->_previousTag = $this->_detectMatch[1];
			$str = '</p>' . str_repeat('<blockquote>', -$d) . '<p>';
		} else {
			if ($this->_firstLine)
				$this->_firstLine = false;
			else
				$str = '<br />';
		}
		return ($str . $this->_renderInlineTag($this->_detectMatch[2]));
	}
}

