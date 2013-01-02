<?php

namespace Skriv\Markup\Html;

/**
 * Gestion du texte en indice.
 */
class Subscript extends \WikiRenderer\TagXhtml {
	protected $name = 'sub';
	public $beginTag = ',,';
	public $endTag = ',,';
}

