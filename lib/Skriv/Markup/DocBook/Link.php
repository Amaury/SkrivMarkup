<?php

namespace Skriv\Markup\DocBook;

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
			list($href, $label, $targetBlank, $nofollow) = $this->config->processLink($href, $this->name);
			$this->contents[0] = $label;
		} else {
			$href = $this->wikiContentArr[1];
			list($href, $label, $targetBlank, $nofollow) = $this->config->processLink($href, $this->name);
		}
		$this->contents[0] = trim($this->contents[0]);
		$this->wikiContentArr[1] = $href;
		// management of the target
		$targetBlank = isset($targetBlank) ? $targetBlank : $this->config->getParam('targetBlank');
		$nofollow = isset($nofollow) ? $nofollow : $this->config->getParam('nofollow');
		if (!isset($targetBlank) || !isset($nofollow)) {
			// no targetBlank behaviour defined, check the link
			if ((isset($href[0]) && isset($href[1]) && $href[0] === '/' && $href[1] === '/') ||
			    strpos($href, '://') !== false) {
				if (!isset($targetBlank))
					$targetBlank = true;
				if (!isset($nofollow))
					$nofollow = true;
			}
		}
		if ($targetBlank)
			$this->additionnalAttributes['target'] = '_blank';
		else
			unset($this->additionnalAttributes['target']);
		if ($nofollow)
			$this->additionnalAttributes['rel'] = 'nofollow';
		else
			unset($this->additionnalAttributes['rel']);
		// link generation
		return ('<ulink url="' . htmlspecialchars($href) . '">' . $this->contents[0] . '</ulink>');
	}
}

