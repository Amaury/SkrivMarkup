<?php

namespace Skriv\Markup\Html;

/**
 * traite les signes de type paragraphe
 */
class Paragraph extends \WikiRenderer\Block {
	public $type = 'p';
	protected $_openTag = '<p>';
	protected $_closeTag = '</p>';
	// attribut utilisé pour gérer les retours charriots dans les paragraphes
	private $_firstLine = true;

	/**
	 * Détection des paragraphes
	 * @param	string	$string		Ligne de texte servant pour la détection.
	 * @param	bool	$inBlock	(optional) True is the parser is already in the block.
	 * @return	bool	True si c'est un paragraphe.
	 */
	public function detect($string, $inBlock=false) {
		if (empty($string))
			return (false);
		if (!preg_match("/^\s*\*{2}.*\*{2}\s*.*$/", $string) &&
		    !preg_match("/^\s*#{2}.*#{2}\s*.*$/", $string) &&
		    preg_match("/^\s*[\*#\-\!\| \t>;<=].*/", $string))
			return (false);
		$this->_detectMatch = array($string, $string);
		return (true);
	}
	/**
	 * Traitement du texte à l'intérieur d'un paragraphe.
	 * @param	string	$string	Texte à traiter.
	 * @return	string	Texte traité.
	 */
	protected function _renderInlineTag($string) {
		$string = $this->engine->inlineParser->parse($string);
		// gestion des retours-charriot dans les paragraphes
		$string = (!$this->_firstLine) ? "<br />$string" : $string;
		$this->_firstLine = false;
		return ($string);
	}
}

