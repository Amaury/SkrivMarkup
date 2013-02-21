<?php

namespace Skriv\Markup\DocBook;

/** Gestion du texte souligné. */
class Underline extends \WikiRenderer\TagXhtml {
	protected $name = 'emphasis';
	public $beginTag = '__';
	public $endTag = '__';
	protected $additionnalAttributes = array('role' => 'underline');
}

