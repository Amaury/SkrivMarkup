<?php

namespace Skriv\Markup\Html;

/**
 * Extension sued to generate Lorem Ipsum text.
 * Base on the work of Mathew Tinsley.
 *
 * @see	http://tinsology.net/2009/07/php-lorem-ipsum-generator/
 * @see	https://github.com/classmarkets/LipsumGenerator
 */
class ExtLipsum extends \WikiRenderer\Block {
	/** Regular expression. */
	protected $regexp = "/^<<<lipsum(\|\d+)?>>>\s*/";
	/** One line block. */
	protected $_closeNow = true;
	/** Number of paragraphs. */
	private $_nbrParagraphs = 1;

	/**
	 * Retourne la ligne courant, après traitement.
	 * @return	string	La ligne courante après traitement.
	 */
	public function getRenderedLine() {
		if (isset($this->_detectMatch[1]) && ($nbr = substr($this->_detectMatch[1], 1)) && is_numeric($nbr))
			$this->_nbrParagraphs = $nbr;
		$result = '';
		for ($i = 0; $i < $this->_nbrParagraphs; $i++)
			$result .= $this->_getParagraph($i == 0);
		return ($result);
	}

	/* *************** PRIVATE ATTRIBUTES & METHODS ************* */
	/** Number of words per paragraph. */
	private $_nbrWordsPerParagraph = 100;
	/** Number of words per sentence. */
	private $_nbrWordsPerSentence = 20;
	/** Dictionary. */
	private $_dictionary = array(
		'dolor', 'sit', 'amet', 'consectetur', 'adipiscing',
		'elit', 'curabitur', 'vel', 'hendrerit', 'libero',
		'eleifend', 'blandit', 'nunc', 'ornare', 'odio',
		'ut', 'orci', 'gravida', 'imperdiet', 'nullam',
		'purus', 'lacinia', 'a', 'pretium', 'quis',
		'congue', 'praesent', 'sagittis', 'laoreet', 'auctor',
		'mauris', 'non', 'velit', 'eros', 'dictum',
		'proin', 'accumsan', 'sapien', 'nec', 'massa',
		'volutpat', 'venenatis', 'sed', 'eu', 'molestie',
		'lacus', 'quisque', 'porttitor', 'ligula', 'dui',
		'mollis', 'tempus', 'at', 'magna', 'vestibulum',
		'turpis', 'ac', 'diam', 'tincidunt', 'id',
		'condimentum', 'enim', 'sodales', 'in', 'hac',
		'habitasse', 'platea', 'dictumst', 'aenean', 'neque',
		'fusce', 'augue', 'leo', 'eget', 'semper',
		'mattis', 'tortor', 'scelerisque', 'nulla', 'interdum',
		'tellus', 'malesuada', 'rhoncus', 'porta', 'sem',
		'aliquet', 'et', 'nam', 'suspendisse', 'potenti',
		'vivamus', 'luctus', 'fringilla', 'erat', 'donec',
		'justo', 'vehicula', 'ultricies', 'varius', 'ante',
		'primis', 'faucibus', 'ultrices', 'posuere', 'cubilia',
		'curae', 'etiam', 'cursus', 'aliquam', 'quam',
		'dapibus', 'nisl', 'feugiat', 'egestas', 'class',
		'aptent', 'taciti', 'sociosqu', 'ad', 'litora',
		'torquent', 'per', 'conubia', 'nostra', 'inceptos',
		'himenaeos', 'phasellus', 'nibh', 'pulvinar', 'vitae',
		'urna', 'iaculis', 'lobortis', 'nisi', 'viverra',
		'arcu', 'morbi', 'pellentesque', 'metus', 'commodo',
		'ut', 'facilisis', 'felis', 'tristique', 'ullamcorper',
		'placerat', 'aenean', 'convallis', 'sollicitudin', 'integer',
		'rutrum', 'duis', 'est', 'etiam', 'bibendum',
		'donec', 'pharetra', 'vulputate', 'maecenas', 'mi',
		'fermentum', 'consequat', 'suscipit', 'aliquam', 'habitant',
		'senectus', 'netus', 'fames', 'quisque', 'euismod',
		'curabitur', 'lectus', 'elementum', 'tempor', 'risus',
		'cras'
        );
	/**
	 * Returns a paragraph.
	 * @param	bool	$first	Is it the first paragraph?
	 * @return	string	The HTML result.
	 */
	private function _getParagraph($first=false) {
		$words = $this->_dictionary;
		shuffle($words);
		if ($first) {
			$pre = array('lorem', 'ipsum');
			$words = array_merge($pre, $words);
		}
		$nbrWordsPerParagraph = $this->_nbrWordsPerParagraph + mt_rand(-20, 20);
		$nbrWordsPerSentence = $this->_nbrWordsPerSentence + mt_rand(-8, 8);
		$words = array_slice($words, 0, $nbrWordsPerParagraph);
		$sentences = array_chunk($words, $nbrWordsPerSentence);
		$result = array();
		foreach ($sentences as &$sentence) {
			$this->_punctuate($sentence);
			$result[] = ucfirst(implode(' ', $sentence));
		}
		return ('<p>' . implode(' ', $result) . '</p>');
	}
	/**
	 * Inserts commas and periods in the given word array.
	 * @param	array	$sentence	Array of words.
	 */
	private function _punctuate(&$sentence) {
		$count = count($sentence);
		$sentence[$count - 1] .= '.';
		if ($count < 4)
			return ($sentence);
		$commas = $this->_numberOfCommas($count);
		for ($i = 1; $i <= $commas; $i++) {
			$index = (int)round($i * $count / ($commas + 1));
			if ($index < ($count - 1) && $index > 0)
				$sentence[$index] .= ',';
		}
	}
	/**
	 * Determines the number of commas for a sentence of the given length.
	 * Average and standard deviation are determined superficially.
	 * @param	int	$len	Length of text.
	 * @return	int	Number of commas to insert.
	 */
	private function _numberOfCommas($len) {
		$avg = (float)log($len, 6);
		$stdDev = (float)($avg / 6.000);
		return ((int)round($this->_gauss($avg, $stdDev)));
	}
	/**
	 * The following function is used to compute numbers with a gaussian distribution.
	 * Source: http://us.php.net/manual/en/function.rand.php#53784
	 */
	private function _gauss($m=0.0, $s=1.0) {
		// N(0,1)
		// returns random number with normal distribution:
		//   mean = 0
		//   std dev = 1
		$x = (float)(rand() / (float) getrandmax());
		$y = (float)(rand() / (float) getrandmax());
		return ((sqrt(-2 * log($x)) * cos(2 * pi() * $y)) * $s + $m);
	}
}

