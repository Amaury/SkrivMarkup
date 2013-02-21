<?php

namespace Skriv\Markup\DocBook;

class Anchor extends \WikiRenderer\TagXhtml {
	protected $name = 'anchor';
	public $beginTag = '~~';
	public $endTag = '~~';
	protected $attribute = array('name');
	public $separators = array('|');

	public function getContent() {
		$identifier = $this->config->textToIdentifier($this->wikiContentArr[0]);
		return ('<anchor id="' . $this->config->getParam('anchorsPrefix') . $identifier . '"/>');
	}
}

