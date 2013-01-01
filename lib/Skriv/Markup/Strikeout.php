<?php

namespace Skriv\Markup;

/**
 * Gestion du texte barré.
 */
class Strikeout extends \WikiRenderer\TagXhtml {
	protected $name = 's';
	public $beginTag = '--';
	public $endTag = '--';
}

