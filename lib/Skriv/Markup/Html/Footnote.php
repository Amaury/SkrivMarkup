<?php

namespace Skriv\Markup\Html;

/**
 * Gestion des notes de bas de page.
 * @todo	Gérer les notes identifiées par un titre et non par un numéro.
 */
class Footnote extends \WikiRenderer\TagXhtml {
	protected $name = 'footnote';
	public $beginTag = '((';
	public $endTag = '))';

	public function getContent() {
		// on ajoute la note à la liste des notes de bas de page
		$footnoteIds = $this->config->addFootnote($this->contents[0]);

		$id = $footnoteIds['id'];
		$index = $footnoteIds['index'];

		return "<sup class=\"footnote-ref\"><a href=\"#cite_note-$id\" name=\"cite_ref-$id\" id=\"cite_ref-$id\">$index</a></sup>";
	}
}

