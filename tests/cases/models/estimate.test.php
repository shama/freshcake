<?php
/**
 * Estimate Test
 * 
 * @package freshcake
 * @author Kyle Robinson Young <kyle at kyletyoung.com> 
 */
App::import('Model', array('ConnectionManager', 'Freshbooks.Estimate'));
App::import('Core', array('HttpSocket', 'Xml'));
App::import('Helper', 'Xml');
Mock::generate('HttpSocket');

class EstimateTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Estimate';

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
			'estimates' => array(
				'estimate' => array(
					array(
						'esimate_id' => 13,
						'number' => '1234',
						'client_id' => 13,
						'organization' => 'Test Inc.',
						'first_name' => 'Test',
						'last_name' => 'Person',
						'lines' => array(
							'line' => array(
								array(
									'name' => 'Yard Work',
									'description' => 'Mowed the lawn.',
									'unit_cost' => 10,
									'quantity' => 4,
								),
								array(
									'name' => 'Yard Work 2',
									'description' => 'Mowed the lawn again.',
									'unit_cost' => 10,
									'quantity' => 4,
								),
							),
						),
					),
					array(
						'esimate_id' => 14,
						'number' => '1234',
						'client_id' => 13,
						'organization' => 'Test Inc.',
						'first_name' => 'Testy',
						'last_name' => 'Persony',
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
				'Estimate' => array(
					'esimate_id' => 13,
					'number' => '1234',
					'client_id' => 13,
					'organization' => 'Test Inc.',
					'first_name' => 'Test',
					'last_name' => 'Person',
					'lines' => array(
						'line' => array(
							array(
								'name' => 'Yard Work',
								'description' => 'Mowed the lawn.',
								'unit_cost' => 10,
								'quantity' => 4,
							),
							array(
								'name' => 'Yard Work 2',
								'description' => 'Mowed the lawn again.',
								'unit_cost' => 10,
								'quantity' => 4,
							),
						),
					),
				),
			),
			1 => array(
				'Estimate' => array(
					'esimate_id' => 14,
					'number' => '1234',
					'client_id' => 13,
					'organization' => 'Test Inc.',
					'first_name' => 'Testy',
					'last_name' => 'Persony',
				),
			),
		));
		unset($xml, $node, $res);
		
		// TEST GET
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'estimate' => array(
				'esimate_id' => 13,
				'number' => '1234',
				'client_id' => 13,
				'organization' => 'Test Inc.',
				'first_name' => 'Test',
				'last_name' => 'Person',
			),
		), array('format' => 'tags'));
		$xml->first()->append($node->children);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$res = $this->Model->findById(13);
		$this->assertEqual($res, array(
			'Estimate' => array(
				'esimate_id' => 13,
				'number' => '1234',
				'client_id' => 13,
				'organization' => 'Test Inc.',
				'first_name' => 'Test',
				'last_name' => 'Person',
			),
		));
		unset($xml, $node, $res);
		
	}

/**
 * testSave
 */
	public function testSave() {
		$save_data = array(
			'number' => '1234',
			'client_id' => 13,
			'organization' => 'Test Inc.',
			'first_name' => 'Test',
			'last_name' => 'Person',
		);
		
		// TEST CREATE
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'esimate_id' => 13,
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
 * end
 */
	public function end() {
		unset($this->Ds);
		unset($this->Model);
	}

}