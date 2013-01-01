<?php

namespace Skriv\Markup;

class Link extends \WikiRenderer\TagXhtml {
	protected $name = 'a';
	public $beginTag = '[[';
	public $endTag = ']]';
	protected $attribute = array('$$', 'href');
	public $separators = array('|');

	public function getContent() {
		// gestion du paramètre unique
		if ($this->separatorCount == 0) {
			$this->separatorCount = 1;
			$href = $this->wikiContentArr[0];
			list($href, $label) = $this->config->processLink($href, $this->name);
			$this->contents[0] = $label;
		} else {
			$href = $this->wikiContentArr[1];
			list($href, $label) = $this->config->processLink($href, $this->name);
		}
		$this->contents[0] = trim($this->contents[0]);
		$this->wikiContentArr[1] = $href;
		// on retourne le lien généré
		return parent::getContent();
	}
}

