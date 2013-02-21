<?php

namespace Skriv\Markup\DocBook;

/**
 * traite les signes de types table
 */
class Table extends \WikiRenderer\Block {
	public $type = 'table';
	protected $regexp = "/^(!!|\|\|) ?(.*)/";
	protected $_openTag = '<table><tbody>';
	protected $_closeTag = '</tbody></table>';
	protected $_colcount = 0;

	public function open() {
		$this->_colcount = 0;
		return ($this->_openTag);
	}
	public function getRenderedLine() {
		$str = '';
		$text = ' ' . $this->_detectMatch[0];
		$prevPos = 0;
		$prevType = '';
		$loop = true;
		while ($loop) {
			if (($posTh = strpos($text, '!!', $prevPos)) === false &&
			    ($posTd = strpos($text, '||', $prevPos)) === false) {
				$posTh = false;
				$posTd = strlen($text);
				$loop = false;
			}
			if ($posTh === false || (is_int($posTd) && $posTd < $posTh)) {
				$pos = $posTd;
				$type = 'entry';
			} else {
				$pos = $posTh;
				$type = 'th';
			}
			if ($prevPos) {
				$cell = substr($text, $prevPos, $pos - $prevPos);
				$str .= '<entry' . (($prevType == 'th') ? ' role="head"' : '') . '>' . $this->_renderInlineTag(trim($cell)) . '</entry>';
			}
			$prevPos = $pos + 3;
			$prevType = $type;
		}
		return ("<row>$str</row>");
	}
}

