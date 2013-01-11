<?php

namespace Skriv\Markup\Html;

/**
 * Process of titles.
 *
 * @author	Amaury Bouchard <amaury@amaury.net>
 * @copyright	© 2012-2013
 * @package	SkrivMarkup
 * @subpackage	Html
 */
class Title extends \WikiRenderer\Block {
	public $type = 'title';
	protected $regexp = "/^(={1,6})(.*)\s*$/";
	protected $_closeNow = true;

	public function getRenderedLine() {
		$equals = $this->_detectMatch[1];
		$text = trim($this->_detectMatch[2]);
		$text = rtrim(rtrim($text, '='));
		$level = strlen($equals);

		$html = $this->_renderInlineTag($text);
		$identifier = $this->engine->getConfig()->titleToIdentifier($level, $html);

		$this->engine->getConfig()->addTocEntry($level, $html);
		$level += $this->engine->getConfig()->getParam('firstTitleLevel') - 1;

		return ("<h$level id=\"" . $this->engine->getConfig()->getParam('anchorsPrefix') . "$identifier\">$html</h$level>");
	}
}

