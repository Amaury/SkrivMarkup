<?php

namespace Skriv\Markup\DocBook;

/**
 * Gestion du texte barré.
 *
 * @see	http://stackoverflow.com/questions/9588299/docbook-and-strikethrough-functionality
 */
class Strikeout extends \WikiRenderer\TagXhtml {
	protected $name = 'emphasis';
	public $beginTag = '--';
	public $endTag = '--';
	protected $additionnalAttributes = array('role' => 'strikeout');
}

