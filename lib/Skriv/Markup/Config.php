<?php

namespace Skriv\Markup;

/**
 * SkrivMarkup configuration object.
 * This object is based on the WikiRenderer project created by Laurent Jouanneau.
 *
 * @author	Amaury Bouchard <amaury@amaury.net>
 * @copyright	© 2012-2013, Amaury Bouchard
 * @package	SkrivMarkup
 * @see		WikiRenderer
 */
class Config extends \WikiRenderer\Config  {
	/** ??? */
	public $defaultTextLineContainer = '\WikiRenderer\HtmlTextLine';
	/** List of inline markups. */
	public $textLineContainers = array(
		'\WikiRenderer\HtmlTextLine' => array(
			'\Skriv\Markup\Strong',		// **strong**
			'\Skriv\Markup\Em',		// ''em''
			'\Skriv\Markup\Strikeout',	// --strikeout--
			'\Skriv\Markup\Underline',	// __underline__
			'\Skriv\Markup\Monospace',	// ##monospace##
			'\Skriv\Markup\Superscript',	// ^^superscript^^
			'\Skriv\Markup\Subscript',	// ,,subscript,,
			'\Skriv\Markup\Abbr',		// ??abbr|text??
			'\Skriv\Markup\Link',		// [[link|url]]		[[url]]
			'\Skriv\Markup\Image',		// {{image|url}}	{{url}}
			'\Skriv\Markup\Footnote'	// ((footnote))		((label|footnote))
		)
	);
	/** List of bloc markups. */
	public $blocktags = array(
		'\Skriv\Markup\Title',
		'\Skriv\Markup\WikiList',
		'\Skriv\Markup\Code',
		'\Skriv\Markup\Pre',
		'\Skriv\Markup\Hr',
		'\Skriv\Markup\Blockquote',
		'\Skriv\Markup\Table',
		'\Skriv\Markup\StyledBlock',
		'\Skriv\Markup\Paragraph',
	);

	/* ************ SKRIV MARKUP SPECIFIC ATTRIBUTES ************* */
	/** List of the footnotes being created. */
	private $_footnotes = null;
	/** Parent configuration object, used for recursive calls. */
	private $_parentConfig = null;
	/** Hash containing the configuration parameters. */
	private $_params = null;

	/* ******************** CONSTRUCTION ****************** */
	/**
	 * Constructor.
	 * @param	array	$param		(optionnel) Hash containing specific configuration parameters.
	 *					- int		skrivElementId		Identifier of the currently processed Skriv element.
	 *					- bool		shortenLongUrl		Specifies if we must shorten URLs longer than 40 characters.
	 *					- bool		processSkrivLinks	Specifies if we must process Skriv-specific URLs.
	 *					- Closure	urlProcessFunction	URLs processing function.
	 *					- string	footnotesPrefix		Prefix of footnotes' identifiers.
	 *					- string	anchorsPrefix		Prefix of anchors' identifiers.
	 * @param	\Skriv\Markup\Config	parentConfig	Parent configuration object, for recursive calls.
	 */
	public function __construct($param=null, \Skriv\Markup\Config $parentConfig=null) {
		// creation of the default parameters array
		$randomId = base_convert(rand(0, 50000), 10, 36);
		$this->_params = array(
			'skrivElementId'	=> null,
			'shortenLongUrl'	=> true,
			'processSkrivLinks'	=> false,
			'urlProcessFunction'	=> null,
			'anchorsPrefix'		=> "skriv-$randomId",
			'footnotesPrefix'	=> "skriv-notes-$randomId"
		);
		// processing of specified parameters
		if (isset($param['skrivElementId']))
			$this->_params['skrivElementId'] = $param['skrivElementId'];
		if (isset($param['shortenLongUrl']) && $param['shortenLongUrl'] === false)
			$this->_params['shortenLongUrl'] = false;
		if (isset($param['processSkrivLinks']) && $param['processSkrivLinks'] === true)
			$this->_params['processSkrivLinks'] = true;
		if (isset($param['urlProcessFunction']) && is_a($param['urlProcessFunction'], 'Closure'))
			$this->_params['urlProcessFunction'] = $param['urlProcessFunction'];
		if (isset($param['anchorsPrefix']))
			$this->_params['anchorsPrefix'] = $param['anchorsPrefix'];
		if (isset($param['footnotesPrefix']))
			$this->_params['footnotesPrefix'] = $param['footnotesPrefix'];
		// storing the parent configuration object
		$this->_parentConfig = $parentConfig;
		// footnotes liste init
		$this->_footnotes = array();
	}
	/**
	 * Build an object of the same type, "child" of the current object.
	 * @return	\Skriv\Markup\Config	The new configuration object.
	 */
	public function subConstruct() {
		return (new Config($this->_params, $this));
	}
	/**
	 * Returns a specific configuration parameter. If a parent configuration object exists, the parameter is asked to it.
	 * @param	string	$param	Parameter's name.
	 * @return	mixed	Value of the configuration parameter.
	 */
	public function getParam($param) {
		if (isset($this->_parentConfig))
			return ($this->_parentConfig->getParam($param));
		return (isset($this->_params[$param]) ? $this->_params[$param] : null);
	}
	/**
	 * Method called for pre-parse processing.
	 * @param	string	$text	The input text.
	 * @return	string	The text that will be parsed.
	 */
	public function onStart($text) {
		// process of smileys and other special characters
		$text = Smiley::process($text);
		// process of email addresses
		$text = preg_replace_callback("/([\|\[ ]*[i\w:#\.-]+@[\w\.-]*[\w-]\.[\w\.-]+[\|\] ]*)/", function($matches) {
			$str = trim($matches[0]);
			$lastc = substr($str, -1);
			if ($str[0] == '|' || $str[0] == '[' || $lastc == ']' || $lastc == '|')
				return ($matches[0]);
			$result = '[[';
			if (substr($str, 0, strlen('mailto:')) === 'mailto:')
				$result .= substr($str, strlen('mailto:')) . "|$str]]";
			else
				$result .= "$str|mailto:$str]]";
			// set back spaces at the beginning
			$result = str_repeat(' ', (strlen(rtrim($matches[0])) - strlen(trim($matches[0])))) . $result;
			// set back spaces at the end
			$result .= str_repeat(' ', (strlen(ltrim($matches[0])) - strlen(trim($matches[0]))));
			return ($result);
		}, $text);
		// process of URLs not written between [[ and ]]
		$text = preg_replace_callback("/([\|\[ ]*\w+:\/\/[^\s\{\}\[\]\*]+[\|\] ]*)/", function($matches) {
			$str = trim($matches[0]);
			$lastc = substr($str, -1);
			if ($str[0] == '|' || $str[0] == '[' || $lastc == ']' || $lastc == '|')
				return ($matches[0]);
			$result = "[[$str]]";
			// set back spaces at the beginning
			$result = str_repeat(' ', (strlen(rtrim($matches[0])) - strlen(trim($matches[0])))) . $result;
			// set back spaces at the end
			$result .= str_repeat(' ', (strlen(ltrim($matches[0])) - strlen(trim($matches[0]))));
			return ($result);
		}, $text);
		// Skriv-specific process
		if ($this->getParam('processSkrivLinks')) {
			// process of references to Skriv elements (like S#123) when they are not written between [[ and ]]
			$text = preg_replace_callback("/([\|\[ ]*[sS]#\d+[\|\] ]*)/", function($matches) {
				$str = trim($matches[0]);
				$lastc = substr($str, -1);
				if ($str[0] == '|' || $str[0] == '[' || $lastc == ']' || $lastc == '|')
					return ($matches[0]);
				$result = "[[$str]]";
				// set back spaces at the beginning
				$result = str_repeat(' ', (strlen(rtrim($matches[0])) - strlen(trim($matches[0])))) . $result;
				// set back spaces at the end
				$result .= str_repeat(' ', (strlen(ltrim($matches[0])) - strlen(trim($matches[0]))));
				return ($result);
			}, $text);
		}
		return ($text);
	}
	/**
	 * Method called for post-parse processing.
	 * @param	string	$finalText	The generated text.
	 * @return	string	The text after post-processing.
	 */
	public function onParse($finalText) {
		return ($finalText);
	}
	/**
	 * Add a footnote.
	 * @param	string	$text	Footnote's text.
	 * @param	string	$label	(optionnel) Footnote's label. If not given, an auto-incremented
	 *				number will be used.
	 * @return	array	Hash with 'id' and 'index' keys.
	 */
	public function addFootnote($text, $label=null) {
		if (isset($this->_parentConfig))
			return ($this->_parentConfig->addFootnote($text, $label));
		if (is_null($label))
			$this->_footnotes[] = $text;
		else
			$this->_footnotes[] = array(
				'label'	=> $label,
				'text'	=> $text
			);
		$index = count($this->_footnotes);
		return (array(
			'id'	=> $this->getParam('footnotesPrefix') . "-$index",
			'index'	=> $index
		));
	}
	/**
	 * Returns the footnotes content. By default, the rendered HTML is returned, but the
	 * raw list of footnotes is available.
	 * @param	bool	$raw	(optional) Set to True to get the raw list of footnotes.
	 *				False by default.
	 * @return	string|array	The footnotes' rendered HTML or the list of footnotes.
	 */
	public function getFootnotes($raw=false) {
		if ($raw === true)
			return ($this->_footnotes);
		if (empty($this->_footnotes))
			return (null);
		$footnotes = '';
		$index = 1;
		foreach ($this->_footnotes as $note) {
			$id = $this->getParam('footnotesPrefix') . "-$index";
			$noteHtml = "<p class=\"footnote\"><a href=\"#cite_ref-$id\" name=\"cite_note-$id\" id=\"cite_note-$id\">";
			if (is_string($note))
				$noteHtml .= "$index</a>. $note";
			else
				$noteHtml .= htmlspecialchars($note['label']) . "</a>. " . $note['text'];
			$noteHtml .= "</p>\n";
			$footnotes .= $noteHtml;
			$index++;
		}
		$footnotes = "<div class=\"footnotes\">\n$footnotes</div>\n";
		return ($footnotes);
	}
	/**
	 * Links processing.
	 * @param	string	$url		The URL to process.
	 * @param	string	$tagName	Name of the calling tag.
	 * @return	array	Array with the processed URL and the generated label.
	 */
	public function processLink($url, $tagName='') {
		$label = $url = trim($url);
		// shortening of long URLs
		if ($this->getParam('shortenLongUrl') && strlen($label) > 40)
			$label = substr($label, 0, 40) . '...';
		// Javascript XSS check
		if (substr($url, 0, strlen('javascript:')) === 'javascript:')
			$url = '#';
		else {
			// email check
			if (filter_var($url, FILTER_VALIDATE_EMAIL))
				$url = "mailto:$url";
			else if (substr($url, 0, strlen('mailto:')) === 'mailto:')
				$label = substr($url, strlen('mailto:'));
			else if ($this->getParam('processSkrivLinks')) {
				// process of Skriv internal links
				if (preg_match("/^#\d+$/", $url) === 1)
					$url = '/' . substr($url, 1);
				else if (preg_match("/^[sS]#\d+$/", $url) === 1) {
					$label = substr($url, 1);
					$url = '/' . substr($url, 2);
				} else {
					// process of Skriv file attachments
					if (isset($url[0]) && $url[0] != '/' && !preg_match("/^\w+:\/\//", $url))
						$url = '/file/find/' . $this->_skrivElementId . "/$url";
				}
			}
			// if a specific URL process function was defined, it is called
			$func = $this->getParam('urlProcessFunction');
			if (isset($func))
				$url = $func($url);
		}
		
		return (array($url, $label));
	}
}

