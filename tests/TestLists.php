<?php

/**
 * Unit tests for Skriv Markup Language's lists.
 *
 * @package	SkrivMarkup
 * @subpackage	tests
 * @author	Amaury Bouchard <amaury@amaury.net>
 * @copyright	©2013 Amaury Bouchard
 */

class TestLists extends PHPUnit_Framework_TestCase {
	/** Simple tests. */
	public function testSimpleList() {
		$tests = array(
			'base'	=> array(
				"*aaa bbb ccc",
				"<ul>\n<li>aaa bbb ccc</li></ul>"
			),
			'space'		=> array(
				"* aaa bbb",
				"<ul>\n<li>aaa bbb</li></ul>"
			),
			'multilines'	=> array(
				"* aaa bbb\n* ccc ddd",
				"<ul>\n<li>aaa bbb\n</li>\n<li>ccc ddd</li></ul>"
			),
			'sublist'	=> array(
				"* aaa bbb\n* ccc ddd\n** eee fff\n** ggg hhh\n* iii jjj",
				"<ul>\n<li>aaa bbb\n</li>\n<li>ccc ddd\n<ul><li>eee fff\n</li>\n<li>ggg hhh\n</li></ul>\n</li>\n<li>iii jjj</li></ul>"
			)
		);
		$this->_executeTests($tests);
	}
	/** Test of ordered lists. */
	public function testOrderedLists() {
		$tests = array(
			'base'	=> array(
				"#aaa bbb ccc",
				"<ol>\n<li>aaa bbb ccc</li></ol>"
			),
			'space'		=> array(
				"# aaa bbb",
				"<ol>\n<li>aaa bbb</li></ol>"
			),
			'multilines'	=> array(
				"# aaa bbb\n# ccc ddd",
				"<ol>\n<li>aaa bbb\n</li>\n<li>ccc ddd</li></ol>"
			),
			'sublist'	=> array(
				"# aaa bbb\n# ccc ddd\n## eee fff\n## ggg hhh\n# iii jjj",
				"<ol>\n<li>aaa bbb\n</li>\n<li>ccc ddd\n<ol><li>eee fff\n</li>\n<li>ggg hhh\n</li></ol>\n</li>\n<li>iii jjj</li></ol>"
			)
		);
		$this->_executeTests($tests);
	}
	/** Test conflicts with other markups. */
	public function testConflicts() {
		$tests = array(
			'strong'	=> array(
				'**strong text** and some text',
				'<p><strong>strong text</strong> and some text</p>'
			),
			'monospace'	=> array(
				'##monospace text## and some text',
				'<p><tt>monospace text</tt> and some text</p>'
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
			$this->assertEquals(trim($expected), trim($res), "Error for '$name'.");
			$this->assertEquals(0, count($skriv->getErrors()), "Errors detected by WikiRenderer.");
		}
	}
}

