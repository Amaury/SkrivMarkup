<?php

namespace Skriv\Markup\Html;

/**
 * Gestion du texte en gras.
 */
class Strong extends \WikiRenderer\TagXhtml {
	protected $name = 'strong';
	public $beginTag = '**';
	public $endTag = '**';
}

