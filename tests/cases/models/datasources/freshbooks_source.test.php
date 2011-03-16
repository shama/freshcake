<?php
/**
 * Freshbooks Source Test
 *
 * @package freshcake
 * @author Kyle Robinson Young <kyle at dontkry.com>
 *
 */
App::import('Datasource', 'Freshbooks.FreshbooksSource');
class TestFreshbooksSource extends FreshbooksSource {

/**
 * parseResponse
 * @param string $response
 * @return array
 */
	function parseResponse($response=null) {
		return $this->_parseResponse($response);
	}

}
class FreshbooksSourceTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Freshbooks';

/**
 * Model
 * @var object
 */
	public $Model = null;

/**
 * Ds
 * @var object
 */
	public $Ds = null;

/**
 * start
 */
	public function start() {
		$this->Ds =& new TestFreshbooksSource(array());

	}

/**
 * testParseResponse
 */
	public function testParseResponse() {

		// BLANK RESPONSE
		$this->assertFalse($this->Ds->parseResponse());

		// OK RESPONSE
		$xml =& new Xml(array(
			'response' => array(
				'status' => 'ok',
				'callback_id' => array('20'),
			),
		));
		$res = $this->Ds->parseResponse($xml->toString(true));
		$this->assertEqual($res, array(
			'Response' => array(
				'status' => 'ok',
				'callback_id' => 20,
			),
		));

		// FAIL RESPONSE
		$xml =& new Xml(array(
			'response' => array(
				'status' => 'fail',
				'error' => array('I have failed you!'),
			),
		));
		try {
			$res = $this->Ds->parseResponse($xml->toString(true));
			$this->assertFalse($res);
		} catch (Exception $e) {
			$this->assertEqual($e->getMessage(), 'I have failed you!');
		}

		// MALFORMED RESPONSE
		$xml =& new Xml(array(
			'response' => array(
				'status' => 'wtf',
				'what' => array('is this response?'),
			),
		));
		try {
			$res = $this->Ds->parseResponse($xml->toString(true));
			$this->assertFalse($res);
		} catch (Exception $e) {
			$this->assertEqual($e->getMessage(), 'An unknown error occurred.');
		}
	}

/**
 * end
 */
	public function end() {
		Cache::clear(false, 'freshbooks');
	}

}