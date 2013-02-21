<?php

namespace Skriv\Markup\DocBook;

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
		$alt = ($alt === $src) ? '' : "<textobject><phrase>$alt</phrase></textobject>";
		// on retourne le lien généré
		return "<img src=\"$src\" alt=\"$alt\" />";
		return ('<inlinemediaobject><imageobject><imagedata fileref="' . htmlspecialchar($src) . '/></imageobject>' . $alt . '</inlinemediaobject>');
	}
}

