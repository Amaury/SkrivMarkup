<?php

namespace Skriv\Markup\DocBook;

/**
 * Gestion du texte en exposant.
 */
class Superscript extends \WikiRenderer\TagXhtml {
	protected $name = 'superscript';
	public $beginTag = '^^';
	public $endTag = '^^';
}

