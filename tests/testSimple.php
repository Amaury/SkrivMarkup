<?php

/**
 * Basical unit tests for Skriv Markup Language
 *
 * @package	SkrivMarkup
 * @subpackage	tests
 * @author	Amaury Bouchard <amaury@amaury.net>
 * @copyright	©2013 Amaury Bouchard
 */

class TestSimple extends PHPUnit_Framework_TestCase {
	/** Simple tests. */
	public function testSimple() {
		$tests = array(
			'base'	=> array(
				"aaa bbb ccc",
				"<p>aaa bbb ccc</p>"
			),
			'strong'	=> array(
				"aaa **bbb** ccc",
				"<p>aaa <strong>bbb</strong> ccc</p>"
			),
			'emphasis'	=> array(
				"aaa ''bbb'' ccc",
				"<p>aaa <em>bbb</em> ccc</p>"
			),
			'underline'	=> array(
				"aaa __bbb__ ccc",
				"<p>aaa <u>bbb</u> ccc</p>"
			),
			'monospace'	=> array(
				"aaa ##bbb## ccc",
				"<p>aaa <tt>bbb</tt> ccc</p>"
			),
			'superscript'	=> array(
				"aaa ^^bbb^^ ccc",
				"<p>aaa <sup>bbb</sup> ccc</p>"
			),
			'subscript'	=> array(
				"aaa ,,bbb,, ccc",
				"<p>aaa <sub>bbb</sub> ccc</p>"
			)
		);
		$this->_executeTests($tests);
	}
	/** Titles test. */
	function testTitles() {
		$tests = array(
			'h1'	=> array(
				"=Title",
				'<h1 id="Title">Title</h1>'
			),
			'h1b'	=> array(
				"=Title=",
				'<h1 id="Title">Title</h1>'
			),
			'h2'	=> array(
				"==Title",
				'<h2 id="Title">Title</h2>'
			),
			'h2b'	=> array(
				"==Title==",
				'<h2 id="Title">Title</h2>'
			),
			'h3'	=> array(
				"===Title",
				'<h3 id="Title">Title</h3>'
			),
			'h3b'	=> array(
				"===Title===",
				'<h3 id="Title">Title</h3>'
			),
			'h4'	=> array(
				"====Title",
				'<h4 id="Title">Title</h4>'
			),
			'h4b'	=> array(
				"====Title====",
				'<h4 id="Title">Title</h4>'
			),
			'h5'	=> array(
				"=====Title",
				'<h5 id="Title">Title</h5>'
			),
			'h5b'	=> array(
				"=====Title=====",
				'<h5 id="Title">Title</h5>'
			),
			'h6'	=> array(
				"======Title",
				'<h6 id="Title">Title</h6>'
			),
			'h6b'	=> array(
				"======Title======",
				'<h6 id="Title">Title</h6>'
			),
		);
		$this->_executeTests($tests);
	}
	/** Links test. */
	public function testLinks() {
		$tests = array(
			'direct HTTP'	=> array(
				'http://skriv.org',
				'<p><a href="http://skriv.org">http://skriv.org</a></p>'
			),
			'direct FTP'	=> array(
				'ftp://server.com/path/file.jpg',
				'<p><a href="ftp://server.com/path/file.jpg">ftp://server.com/path/file.jpg</a></p>'
			),
			'direct email'	=> array(
				'amaury@amaury.net',
				'<p><a href="mailto:amaury@amaury.net">amaury@amaury.net</a></p>'
			),
			'direct mailto'	=> array(
				'mailto:amaury@amaury.net',
				'<p><a href="mailto:amaury@amaury.net">amaury@amaury.net</a></p>'
			),
			'direct long URL'	=> array(
				'http://server.com/very/very/very/long/url/like/you/never/saw/on/the/internet',
				'<p><a href="http://server.com/very/very/very/long/url/like/you/never/saw/on/the/internet">http://server.com/very/very/very/long/ur...</a></p>'
			),
			'simple link'	=> array(
				'[[http://skriv.org]]',
				'<p><a href="http://skriv.org">http://skriv.org</a></p>'
			),
			'simple long URL'	=> array(
				'[[http://server.com/very/very/very/long/url/like/you/never/saw/on/the/internet]]',
				'<p><a href="http://server.com/very/very/very/long/url/like/you/never/saw/on/the/internet">http://server.com/very/very/very/long/ur...</a></p>'
			),
			'titled link'	=> array(
				'[[Google Reader|http://www.google.com/reader]]',
				'<p><a href="http://www.google.com/reader">Google Reader</a></p>'
			),
			'spaced link'	=> array(
				'[[ Google Reader |  http://www.google.com/reader ]]',
				'<p><a href="http://www.google.com/reader">Google Reader</a></p>'
			),
			'strong link'	=> array(
				'[[A **good** site|http://osnews.com]]',
				'<p><a href="http://osnews.com">A <strong>good</strong> site</a></p>'
			),
			'strong link 2'	=> array(
				'**[[Skriv | http://skriv.org]]**',
				'<p><strong><a href="http://skriv.org">Skriv</a></strong></p>'
			)
		);
		$this->_executeTests($tests);
	}
	/** Images test. */
	public function testImages() {
		$tests = array(
			'simple'	=> array(
				'{{http://skriv.org/logo.png}}',
				'<p><img src="http://skriv.org/logo.png" /></p>'
			),
			'titled'	=> array(
				'{{Skriv | http://skriv.org/logo2.png}}',
				'<p><img src="http://skriv.org/logo2.png" alt="Skriv" /></p>'
			),
			'linked'	=> array(
				'[[ {{Skriv|http://skriv.org/logo3.png}} | http://skriv.org]]',
				'<p><a href="http://skriv.org"><img src="http://skriv.org/logo3.png" title="Skriv" /></a></p>'
			)
		);
		$this->_executeTests($tests);
	}
	/** Horizontal rules test. */
	public function testHorizontalRules() {
		$tests = array(
			'false'	=> array(
				'---',
				'<p>---</p>'
			),
			'mini'	=> array(
				'----',
				'<hr />'
			),
			'maxi'	=> array(
				'-------------------',
				'<hr />'
			)
		);
		$this->_executeTests($tests);
	}
	/** Abbreviations test. */
	public function testAbbreviations() {
		$tests = array(
			'full'	=> array(
				'??OABP|Open Address Book Protocol??',
				'<p><abbr title="Open Address Book Protocol">OABP</abbr></p>'
			)
		);
		$this->_executeTests($tests);
	}

	/* ****************** PRIVATE METHODS ************** */
	/**
	 * Takes a list of tests, and run them.
	 * @param	array	$tests	List of tests.
	 */
	private function _executeTests($tests) {
		$skriv = \Skriv\Markup\Renderer::factory();
		foreach ($tests as $name => $data) {
			list($source, $expected) = $data;
			$res = $skriv->render($source);
			$this->assertEquals($expected, $res, "Error for '$name'.");
			$this->assertEquals(0, count($skriv->getErrors()), "Errors detected by WikiRenderer.");
		}
	}
}

