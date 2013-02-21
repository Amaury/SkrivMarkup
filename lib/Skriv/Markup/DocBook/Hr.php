<?php

namespace Skriv\Markup\DocBook;

/**
 * traite les signes de types hr
 */
class Hr extends \WikiRenderer\Block {
	public $type = 'hr';
	protected $regexp = '/^-{4,}$/';
	protected $_closeNow = true;

	public function getRenderedLine() {
		return '';
	}
}

