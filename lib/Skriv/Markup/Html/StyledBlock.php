<?php

namespace Skriv\Markup\Html;

/** Gestion des paragraphes avec style CSS. */
class StyledBlock extends \WikiRenderer\Block {
	public $type = 'div';
	protected $_openTag = '<div class="%s">';
	protected $_closeTag = '</div>';
	protected $isOpen = false;
	/** Classes CSS des blocs stylés. */
	private $_cssClasses = '';
	/** Contenu brut du bloc stylé courant. */
	private $_currentContent = '';
	/** Nombre de récursions. */
	private $_recursionDepth = 0;
	/** This object shouldn't be cloned. */
	protected $_mustClone = false;

	/**
	 * Retourne le tag fermant, et positionne le flag interne pour dire qu'on est à l'intérieur d'un bloc stylisé.
	 * @return	string	Le tag ouvrant.
	 */
	public function open() {
		$this->isOpen = true;
		return null;
	}
	/**
	 * Retourne le tag fermant, et positionne le flag interne pour dire qu'on n'est plus à l'intérieur d'un bloc stylisé.
	 * @return	string	Le tag fermant.
	 */
	public function close() {
		$this->isOpen = false;
		// remise à zéro
		$this->_recursionDepth = 0;
		$subContent = $this->_currentContent;
		$this->_currentContent = '';
		//traitement récursif
		$cfg = $this->engine->getConfig()->subConstruct();
		$engine = new \WikiRenderer\Renderer($cfg);
		$html = sprintf($this->_openTag, $this->_cssClasses) . "\n" .
			$engine->render($subContent) .
			$this->_closeTag;
		return $html;
	}
	/**
	 * Retourne la ligne courant, après traitement.
	 * @return	string	La ligne courante après traitement.
	 */
	public function getRenderedLine() {
		return false;
	}
	/**
	 * Détecte si on est au début ou à la fin d'un bloc stylisé.
	 * @param	string	$string		La chaîne à analyser.
	 * @param	bool	$inBlock	(optional) True if the parser is already in the block.
	 * @return	bool	True si le début ou la fin de bloc a été trouvée.
	 */
	public function detect($string, $inBlock=false) {
		if ($this->isOpen) {
			if (isset($string[2]) && $string[0] === '}' && $string[1] === '}' && $string[2] === '}') {
				$this->_recursionDepth--;
				if ($this->_recursionDepth === 0)
					$this->isOpen = false;
			} else if (isset($string[2]) && $string[0] === '{' && $string[1] === '{' && $string[2] === '{')
				$this->_recursionDepth++;
			if ($this->isOpen)
				$this->_currentContent .= $string . "\n";
			return true;
		}
		if (isset($string[2]) && $string[0] === '{' && $string[1] === '{' && $string[2] === '{') {
			if ($this->_recursionDepth === 0)
				$this->_cssClasses = trim(substr($string, 3), ' {');
			$this->_recursionDepth++;
			return true;
		}
		return false;
	}
}

