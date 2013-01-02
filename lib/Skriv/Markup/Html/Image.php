<?php

namespace Skriv\Markup\Html;

class Image extends \WikiRenderer\TagXhtml {
	protected $name = 'image';
	public $beginTag = '{{';
	public $endTag = '}}';
	protected $attribute = array('alt', 'src');
	public $separators = array('|');

	public function getContent() {
		$alt = trim($this->wikiContentArr[0]);
		// gestion du paramètre unique
		if ($this->separatorCount == 0)
			$src = $alt;
		else
			$src = $this->wikiContentArr[1];
		$processedLink = $this->config->processLink($src, $this->name);
		$src = $processedLink[0];
		// on retourne le lien généré
		return "<img src=\"$src\" alt=\"$alt\" />";
	}
}

