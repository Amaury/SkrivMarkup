<?php

namespace Skriv\Markup\Html;

/**
 * Gestion de l'italic.
 */
class Em extends \WikiRenderer\TagXhtml {
	protected $name = 'em';
	public $beginTag = '\'\'';
	public $endTag = '\'\'';
}

