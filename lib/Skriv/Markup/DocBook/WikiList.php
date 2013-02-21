<?php

namespace Skriv\Markup\DocBook;

/**
 * traite les signes de types liste
 */
class WikiList extends \WikiRenderer\Block {
	public $type = 'list';
	protected $_previousTag;
	protected $_firstItem = false;
	protected $_firstTagLen;
	protected $regexp = "/^([\*#]+)\s*(.*)/";

	/**
	 * test si la chaine correspond au debut ou au contenu d'un bloc
	 * @param	string	$string
	 * @param	bool	$inBlock	(optional) True if the parser is already in the block.
	 * @return	boolean	true: appartient au bloc
	 */
	public function detect($string, $inBlock=false) {
		if (!preg_match($this->regexp, $string, $this->_detectMatch))
			return (0);
		if ($inBlock !== true && ((substr($string, 0, 2) == '**' && strpos($string, '**', 2) !== false) ||
					  (substr($string, 0, 2) == '##' && strpos($string, '##', 2) !== false)))
			return (0);
		return (1);
	}
	public function open() {
		$this->_previousTag = $this->_detectMatch[1];
		$this->_firstTagLen = strlen($this->_previousTag);
		$this->_firstItem = true;
		if (substr($this->_previousTag, -1, 1) == '#')
			return ("<orderedlist>\n");
		return ("<itemizedlist>\n");
	}
	public function close() {
		$t = $this->_previousTag;
		$str = '';
		for ($i = strlen($t); $i >= $this->_firstTagLen; $i--)
			$str.= ($t[$i - 1] == '#') ? "</listitem></orderedlist>\n" : "</listitem></itemizedlist>\n";
		return ($str);
	}
	public function getRenderedLine() {
		$t = $this->_previousTag;
		$d = strlen($t) - strlen($this->_detectMatch[1]);
		$str = '';
		if ($d > 0) { // on remonte d'un ou plusieurs cran dans la hierarchie...
			$l = strlen($this->_detectMatch[1]);
			for ($i = strlen($t); $i > $l; $i--)
				$str .= ($t[$i - 1] == '#') ? "</listitem></orderedlist>\n" : "</listitem></itemizedlist>\n";
			$str .= "</listitem>\n<listitem>";
			$this->_previousTag = substr($this->_previousTag, 0, -$d); // pour étre sur...
		} else if ($d < 0) { // un niveau de plus
			$c = substr($this->_detectMatch[1], -1, 1);
			$this->_previousTag .= $c;
			$str = ($c == '#') ? "<orderedlist><listitem>" : "<itemizedlist><listitem>";
		} else
			$str = $this->_firstItem ? '<listitem>' : "</listitem>\n<listitem>";
		$this->_firstItem = false;
		return ($str . $this->_renderInlineTag($this->_detectMatch[2]));
	}
}

