<?php

namespace Skriv\Markup\DocBook;

/** Gestion des paragraphes de code. */
class Code extends \WikiRenderer\Block {
	public $type = 'div';
	protected $isOpen = false;
	/** Nom du langage de programmation */
	private $_programmingLanguage = '';
	/** Nombre de récursions. */
	private $_recursionDepth = 0;
	/** This object shouldn't be cloned. */
	protected $_mustClone = false;
	/** Raw content of the code block. */
	private $_currentContent = '';

	/**
	 * Retourne le tag fermant, et positionne le flag interne pour dire qu'on est à l'intérieur d'un bloc stylisé.
	 * @return	string	Le tag ouvrant.
	 */
	public function open() {
		$this->isOpen = true;
		return (null);
	}
	/**
	 * Retourne le tag fermant, et positionne le flag interne pour dire qu'on n'est plus à l'intérieur d'un bloc stylisé.
	 * @return	string	Le tag fermant.
	 */
	public function close() {
		$this->_programmingLanguage = str_replace(array('/', '\\', '..'), '', $this->_programmingLanguage);
		$currentContent = $this->_currentContent;
		$this->_currentContent = '';
		// remove the last carriage-return
		$last2 = substr($currentContent, -2);
		if ($last2 === "\r\n" || $last2 == "\n\r")
			$currentContent = substr($currentContent, 0, -2);
		else if (isset($last2[1]) && ($last2[1] == "\r" || $last2[1] == "\n"))
			$currentContent = substr($currentContent, 0, -1);
		// if no programming language was defined, it's a verbatim block
		if (empty($this->_programmingLanguage))
			return ('<literallayout>' . htmlspecialchars($currentContent) . '</literallayout>');
		return ('<programlisting language="' . $this->_programmingLanguage . '"><![CDATA[' . "$currentContent]]></programlisting>");
	}
	/**
	 * Retourne la ligne courant, après traitement.
	 * @return	string	La ligne courante après traitement.
	 */
	public function getRenderedLine() {
		return (false);
	}
	/**
	 * Détecte si on est au début ou à la fin d'un bloc de code.
	 * @param	string	$string		La chaîne à analyser.
	 * @param	bool	$inBlock	(optional) True if the parser is already in the block.
	 * @return	bool	True si le début ou la fin de bloc a été trouvée.
	 */
	public function detect($string, $inBlock=false) {
		$this->_detectMatch = false;
		if ($this->isOpen) {
			if (isset($string[2]) && $string[0] === ']' && $string[1] === ']' && $string[2] === ']') {
				$this->_recursionDepth--;
				if ($this->_recursionDepth === 0)
					$this->isOpen = false;
			} else if (isset($string[2]) && $string[0] === '[' && $string[1] === '[' && $string[2] === ']')
				$this->_recursionDepth++;
			if ($this->isOpen)
				$this->_currentContent .= $string . "\n";
			return true;
		}
		if (isset($string[2]) && $string[0] === '[' && $string[1] === '[' && $string[2] === '[') {
			if ($this->_recursionDepth === 0)
				$this->_programmingLanguage = trim(substr($string, 3));
			$this->_recursionDepth++;
			return true;
		}
		return false;
	}
}

