<?php

namespace Skriv\Markup\DocBook;

/**
 * Gestion du texte en gras.
 */
class Strong extends \WikiRenderer\TagXhtml {
	protected $name = 'emphasis';
	public $beginTag = '**';
	public $endTag = '**';
	protected $additionnalAttributes = array('role' => 'strong');
}

