<?php

namespace Skriv\Markup\DocBook;

class Abbr extends \WikiRenderer\TagXhtml {
	protected $name = 'abbr';
	public $beginTag = '??';
	public $endTag = '??';
	protected $attribute = array('$$', 'title');
	public $separators = array('|');
	protected $ignoreAttribute = array('title');
}

