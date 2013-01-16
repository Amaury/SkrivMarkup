<?php

namespace Skriv\Markup\Html;

class Link extends \WikiRenderer\TagXhtml {
	protected $name = 'a';
	public $beginTag = '[[';
	public $endTag = ']]';
	protected $attribute = array('$$', 'href');
	public $separators = array('|');

	public function getContent() {
		// management of single parameter
		if ($this->separatorCount == 0) {
			$this->separatorCount = 1;
			$href = $this->wikiContentArr[0];
			list($href, $label, $targetBlank) = $this->config->processLink($href, $this->name);
			$this->contents[0] = $label;
		} else {
			$href = $this->wikiContentArr[1];
			list($href, $label, $targetBlank) = $this->config->processLink($href, $this->name);
		}
		$this->contents[0] = trim($this->contents[0]);
		$this->wikiContentArr[1] = $href;
		// management of the target
		if (!isset($targetBlank))
			$targetBlank = $this->config->getParam('targetBlank');
		if (!isset($targetBlank)) {
			// no targetBlank behaviour defined, check the link
			if ((isset($href[0]) && isset($href[1]) && $href[0] === '/' && $href[1] === '/') ||
			    strpos($href, '://') !== false)
				$targetBlank = true;
		}
		if ($targetBlank)
			$this->additionnalAttributes['target'] = '_blank';
		else
			unset($this->additionnalAttributes['target']);
		// link generation
		return parent::getContent();
	}
}

