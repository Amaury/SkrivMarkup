<?php

namespace Skriv\Markup\Html;

class Anchor extends \WikiRenderer\TagXhtml {
	protected $name = 'anchor';
	public $beginTag = '~~';
	public $endTag = '~~';
	protected $attribute = array('name');
	public $separators = array('|');

	public function getContent() {
		$identifier = $this->config->textToIdentifier($this->wikiContentArr[0]);
		return ('<a id="' . $this->config->getParam('anchorsPrefix') . $identifier . '"></a>');
	}
}

