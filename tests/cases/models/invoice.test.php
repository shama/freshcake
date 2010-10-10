<?php
/**
 * Invoice Test
 * 
 * @package freshcake
 * @author Kyle Robinson Young <kyle at kyletyoung.com>
 * 
 *  TODO:
 *  	Add tests for lines
 *  	Add tests for sendByEmail, sendBySnailMail
 */
App::import('Model', array('ConnectionManager', 'Freshbooks.Invoice'));
App::import('Core', array('HttpSocket', 'Xml'));
App::import('Helper', 'Xml');
Mock::generate('HttpSocket');

class InvoiceTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Invoice';

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
			'invoices' => array(
				'invoice' => array(
					array(
						'invoice_id' => 13,
						'client_id' => 13,
						'number' => '1234',
						'amount' => 99.99,
						'notes' => 'Test',
					),
					array(
						'invoice_id' => 14,
						'client_id' => 13,
						'number' => '1234',
						'amount' => 99.99,
						'notes' => 'Test',
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
				'Invoice' => array(
					'invoice_id' => 13,
					'client_id' => 13,
					'number' => '1234',
					'amount' => 99.99,
					'notes' => 'Test',
				),
			),
			1 => array(
				'Invoice' => array(
					'invoice_id' => 14,
					'client_id' => 13,
					'number' => '1234',
					'amount' => 99.99,
					'notes' => 'Test',
				),
			),
		));
		unset($xml, $node, $res);
		
		// TEST GET
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'invoice' => array(
				'invoice_id' => 13,
				'client_id' => 13,
				'number' => '1234',
				'amount' => 99.99,
				'notes' => 'Test',
			),
		), array('format' => 'tags'));
		$xml->first()->append($node->children);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$res = $this->Model->findById(13);
		$this->assertEqual($res, array(
			'Invoice' => array(
				'invoice_id' => 13,
				'client_id' => 13,
				'number' => '1234',
				'amount' => 99.99,
				'notes' => 'Test',
			),
		));
		unset($xml, $node, $res);
		
	}

/**
 * testSave
 */
	public function testSave() {
		$save_data = array(
			'client_id' => 13,
			'number' => '1234',
			'amount' => 99.99,
			'notes' => 'Test',
		);
		
		// TEST CREATE
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'invoice_id' => 13,
		), array('format' => 'tags'));
		$xml->first()->append($node->children);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->assertTrue($this->Model->save($save_data));
		$this->assertEqual($this->Model->id, 13);
		unset($xml, $node);
		
		// TEST UPDATE
		$xml =& new Xml($this->successXml);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->Model->id = 13;
		$this->assertTrue($this->Model->save($save_data));
		$this->assertEqual($this->Model->id, 13);
		unset($xml, $node);
	}

/**
 * testDelete
 */
	public function testDelete() {
		
		// TEST DELETE
		$xml =& new Xml($this->successXml);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->assertTrue($this->Model->delete(13));
		unset($xml);
		
	}

/**
 * testSendByEmail
 */
	public function testSendByEmail() {
		$xml =& new Xml($this->successXml);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->assertTrue($this->Model->sendByEmail(array(
			'estimate_id' => 13,
			'subject' => 'Test',
			'message' => 'This is a test.',
		)));
		unset($xml);
	}

/**
 * testSendBySnailMail
 */
	public function testSendBySnailMail() {
		$xml =& new Xml($this->successXml);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->assertTrue($this->Model->sendBySnailMail(array(
			'invoice_id' => 13,
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