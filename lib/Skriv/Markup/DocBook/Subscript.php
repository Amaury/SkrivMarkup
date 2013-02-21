<?php

namespace Skriv\Markup\DocBook;

/**
 * Gestion du texte en indice.
 */
class Subscript extends \WikiRenderer\TagXhtml {
	protected $name = 'subscript';
	public $beginTag = ',,';
	public $endTag = ',,';
}

