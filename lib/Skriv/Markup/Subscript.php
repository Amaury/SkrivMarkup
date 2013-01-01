<?php

namespace Skriv\Markup;

/**
 * Gestion du texte en indice.
 */
class Subscript extends \WikiRenderer\TagXhtml {
	protected $name = 'sub';
	public $beginTag = ',,';
	public $endTag = ',,';
}

