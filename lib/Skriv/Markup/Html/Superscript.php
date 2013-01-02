<?php

namespace Skriv\Markup\Html;

/**
 * Gestion du texte en exposant.
 */
class Superscript extends \WikiRenderer\TagXhtml {
	protected $name = 'sup';
	public $beginTag = '^^';
	public $endTag = '^^';
}

