<?php
/**
 * Callback Test
 * 
 * @package freshcake
 * @author Kyle Robinson Young <kyle at kyletyoung.com> 
 */
App::import('Model', array('ConnectionManager', 'Freshbooks.Callback'));
App::import('Core', array('HttpSocket', 'Xml'));
App::import('Helper', 'Xml');
Mock::generate('HttpSocket');

class CallbackTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Callback';
	
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
 * ds_name
 * @var string
 */
	public $ds_name = 'freshbooks_temp';

/**
 * successXml
 * @var array
 */
	public $successXml = array(
		'response' => array(
			'status' => 'ok',
		),
	);

/**
 * start
 */
	public function start() {
		$this->Ds =& ConnectionManager::create($this->ds_name, array(
			'datasource' => 'freshbooks.freshbooks',
			'subdomain' => 'test',
			'token' => '1234',
		));
		if ($this->Ds == null) {
			$this->Ds =& ConnectionManager::getDataSource($this->ds_name);
		}
		$this->Model =& new $this->name(array(
			'alias' => $this->name,
			'ds' => $this->ds_name,
		));
	}

/**
 * testRead
 */
	public function testRead() {
		// TEST LIST
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'callbacks' => array(
				'callback' => array(
					array(
						'callback_id' => 13,
						'uri' => 'http://example.com/webhooks/ready',
						'event' => 'invoice.create',
						'verified' => 1,
					),
					array(
						'callback_id' => 14,
						'uri' => 'http://example.com/webhooks/ready',
						'event' => 'invoice.create',
						'verified' => 1,
					),
				),
			),
		), array('format' => 'tags'));
		$xml->first()->append($node->children);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$res = $this->Model->find('all');
		$this->assertEqual($res, array(
			0 => array(
				'Callback' => array(
					'callback_id' => 13,
					'uri' => 'http://example.com/webhooks/ready',
					'event' => 'invoice.create',
					'verified' => 1,
				),
			),
			1 => array(
				'Callback' => array(
					'callback_id' => 14,
					'uri' => 'http://example.com/webhooks/ready',
					'event' => 'invoice.create',
					'verified' => 1,
				),
			),
		));
		unset($xml, $node, $res);
	}

/**
 * testSave
 */
	public function testSave() {
		$save_data = array(
			'uri' => 'http://example.com/webhooks/ready',
			'event' => 'invoice.create',
		);

		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'callback_id' => 13,
		), array('format' => 'tags'));
		$xml->first()->append($node->children);

		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());

		$this->assertTrue($this->Model->save($save_data));
		$this->assertEqual($this->Model->id, 13);
		unset($xml, $node);
	}

/**
 * testDelete
 */
	public function testDelete() {
		$xml =& new Xml($this->successXml);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->assertTrue($this->Model->delete(13));
		unset($xml);
	}

/**
 * testVerify
 */
	public function testVerify() {
		$xml =& new Xml($this->successXml);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->assertTrue($this->Model->verify(array(
			'callback_id' => 13,
			'verifier' => '1234',
		)));
		unset($xml);
	}

/**
 * testResendToken
 */
	public function testResendToken() {
		$xml =& new Xml($this->successXml);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->assertTrue($this->Model->resendToken(array(
			'callback_id' => 13,
		)));
		unset($xml);
	}

/**
 * end
 */
	public function end() {
		unset($this->Ds);
		unset($this->Model);
	}

}