<?php

namespace Skriv\Markup\DocBook;

/**
 * Gestion des notes de bas de page.
 * @todo	Gérer les notes identifiées par un titre et non par un numéro.
 */
class Footnote extends \WikiRenderer\TagXhtml {
	protected $name = 'footnote';
	public $beginTag = '((';
	public $endTag = '))';

	public function getContent() {
		return ('<footnote><para>' . $this->contents[0] . '</para></footnote>');
	}
}

