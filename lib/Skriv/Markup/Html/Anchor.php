<?php

namespace Skriv\Markup\Html;

class Anchor extends \WikiRenderer\TagXhtml {
	protected $name = 'anchor';
	public $beginTag = '~~';
	public $endTag = '~~';
	protected $attribute = array('name');
	public $separators = array('|');

	public function getContent() {
		return '<a name="' . htmlspecialchars($this->wikiContentArr[0]) . '"></a>';
	}
}

