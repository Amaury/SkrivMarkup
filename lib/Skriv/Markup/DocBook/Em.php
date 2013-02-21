<?php

namespace Skriv\Markup\DocBook;

/**
 * Gestion de l'italic.
 */
class Em extends \WikiRenderer\TagXhtml {
	protected $name = 'emphasis';
	public $beginTag = '\'\'';
	public $endTag = '\'\'';
}

