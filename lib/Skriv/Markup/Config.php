<?php

namespace Skriv\Markup;

class Config extends \WikiRenderer\Config  {
	/** ??? */
	public $defaultTextLineContainer = '\WikiRenderer\HtmlTextLine';
	/** ??? */
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
	/** liste des balises de type bloc reconnus par WikiRenderer. */
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

	/* ************ ATTRIBUTS SPECIFIQUES À SKRIV ************* */
	// la syntaxe Skriv contient la possibilité de mettre des notes de bas de page
	// celles-ci seront stockées ici, avant leur incorporation à la fin du texte.
	private $_footnotes = null;
	private $_footnotesId = 15;
	/** Si c'est un appel récursif, cet attribut pointe sur l'objet "parent". */
	private $_parentConfig = null;
	/** Identifiant de l'élément dont le texte est interprété. */
	private $_skrivElementId = null;

	/**
	 * Constructeur
	 * @param	\Skriv\Markup\Config	$parentConfig	(optionnel) Objet de configuration parent, en cas d'appel récursif.
	 * @param	int			$skrivElementId	(optionnel) Identifiant de l'élément Skriv dont le texte est interprété.
	 */
	public function __construct(\Skriv\Markup\Config $config=null, $skrivElementId=0) {
		$this->_footnotesId = base_convert(rand(0, 50000), 10, 36);
		$this->_footnotes = array();
		$this->_parentConfig = $config;
		$this->_skrivElementId = $skrivElementId;
	}
	/**
	 * Construit un objet du même type, "enfant" de l'objet courant, en l'initialisant correctement.
	 * @param	\Skriv\Markup\Config	Un objet de configuration.
	 */
	public function subConstruct() {
		return (new Config($this, $this->_skrivElementId));
	}
	/**
	 * methode invoquée avant le parsing
	 * @param	string	$text	Le texte qui devait être parsé.
	 * @return	string	Le texte qui sera effectivement parsé.
	 */
	public function onStart($text) {
		// on commence par traiter les smileys et autres caractères particuliers
		$text = Smiley::process($text);
		// gestion des adresses email
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
			// on remet les espaces manquants au début
			$result = str_repeat(' ', (strlen(rtrim($matches[0])) - strlen(trim($matches[0])))) . $result;
			// on remet les espaces manquants à la fin
			$result .= str_repeat(' ', (strlen(ltrim($matches[0])) - strlen(trim($matches[0]))));
			return ($result);
		}, $text);
		// gestion des URL qui ne sont pas placées entre [[ et ]]
		$text = preg_replace_callback("/([\|\[ ]*\w+:\/\/[^\s\{\}\[\]\*]+[\|\] ]*)/", function($matches) {
			$str = trim($matches[0]);
			$lastc = substr($str, -1);
			if ($str[0] == '|' || $str[0] == '[' || $lastc == ']' || $lastc == '|')
				return ($matches[0]);
			$result = "[[$str]]";
			// on remet les espaces manquants au début
			$result = str_repeat(' ', (strlen(rtrim($matches[0])) - strlen(trim($matches[0])))) . $result;
			// on remet les espaces manquants à la fin
			$result .= str_repeat(' ', (strlen(ltrim($matches[0])) - strlen(trim($matches[0]))));
			return ($result);
		}, $text);
		// gestion des références vers des éléments Skriv (S#123) qui ne sont pas placées entre [[ et ]]
		$text = preg_replace_callback("/([\|\[ ]*[sS]#\d+[\|\] ]*)/", function($matches) {
			$str = trim($matches[0]);
			$lastc = substr($str, -1);
			if ($str[0] == '|' || $str[0] == '[' || $lastc == ']' || $lastc == '|')
				return ($matches[0]);
			$result = "[[$str]]";
			// on remet les espaces manquants au début
			$result = str_repeat(' ', (strlen(rtrim($matches[0])) - strlen(trim($matches[0])))) . $result;
			// on remet les espaces manquants à la fin
			$result .= str_repeat(' ', (strlen(ltrim($matches[0])) - strlen(trim($matches[0]))));
			return ($result);
		}, $text);
		return ($text);
	}
	/**
	 * methode invoquée aprés le parsing
	 * @param	string	$finalText	???
	 */
	public function onParse($finalText) {
		return ($finalText);
	}
	/**
	 * Ajoute une note de bas de page.
	 * @param	string	$text	Texte de la note de base de page.
	 * @param	string	$label	(optionnel) Texte du label associé à la note.
	 *				Si aucun label n'est fourni, un numéro auto-incrémenté
	 *				sera utilisé.
	 * @return	array	Hash contenant les clés 'id' et 'index'.
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
			'id'	=> $this->_footnotesId . "-$index",
			'index'	=> $index
		));
	}
	/**
	 * Retourne le texte des notes de bas de page.
	 * @return	string	Le contenu.
	 */
	public function getFootnotes() {
		if (empty($this->_footnotes))
			return null;
		$footnotes = '';
		$index = 1;
		foreach ($this->_footnotes as $note) {
			$id = $this->_footnotesId . "-$index";
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
	 * Traitement des liens. Les XSS Javascript sont détectés.
	 * @param	string	$url		L'URL à traiter.
	 * @param	string	$tagName	Le tag qui manipule cette URL.
	 * @return	array	Tableau contenant l'URL traitée et le label généré.
	 */
	public function processLink($url, $tagName='') {
		$label = $url = trim($url);
		if (strlen($label) > 40)
			$label = substr($label, 0, 40) . '...';
		// vérification pour sécurité
		if (substr($url, 0, strlen('javascript:')) === 'javascript:')
			$url = '#';
		else {
			// vérification pour les emails
			if (filter_var($url, FILTER_VALIDATE_EMAIL))
				$url = "mailto:$url";
			else if (substr($url, 0, strlen('mailto:')) === 'mailto:')
				$label = substr($url, strlen('mailto:'));
			else {
				// traitement des liens Skriv internes
				if (preg_match("/^#\d+$/", $url) === 1)
					$url = '/' . substr($url, 1);
				else if (preg_match("/^[sS]#\d+$/", $url) === 1) {
					$label = substr($url, 1);
					$url = '/' . substr($url, 2);
				} else {
					// traitement des pièces-jointes
					if (isset($url[0]) && $url[0] != '/' && !preg_match("/^\w+:\/\//", $url))
						$url = '/file/find/' . $this->_skrivElementId . "/$url";
				}
			}
		}
		
		return (array($url, $label));
	}
}

