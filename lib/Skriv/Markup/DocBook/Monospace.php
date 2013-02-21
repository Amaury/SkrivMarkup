<?php

namespace Skriv\Markup\DocBook;

/** Gestion de texte monospace. */
class Monospace extends \WikiRenderer\TagXhtml {
	protected $name = 'tt';
	public $beginTag = '##';
	public $endTag = '##';

	public function getContent() {
		$code = $this->wikiContentArr[0];
		return '<code>' . htmlspecialchars($code) . '</code>';
	}
}

