<?php

namespace Skriv\Markup;

/**
 * Gestion du texte en exposant.
 */
class Superscript extends \WikiRenderer\TagXhtml {
	protected $name = 'sup';
	public $beginTag = '^^';
	public $endTag = '^^';
}

