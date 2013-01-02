<?php

namespace Skriv\Markup\Html;

/** Gestion du texte souligné. */
class Underline extends \WikiRenderer\TagXhtml {
	protected $name = 'u';
	public $beginTag = '__';
	public $endTag = '__';
}

