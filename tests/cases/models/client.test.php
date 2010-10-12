<?php
/**
 * Client Test
 * 
 * @package freshcake
 * @author Kyle Robinson Young <kyle at kyletyoung.com> 
 */
App::import('Model', array('ConnectionManager', 'Freshbooks.Client'));
App::import('Core', array('HttpSocket', 'Xml'));
App::import('Helper', 'Xml');
Mock::generate('HttpSocket');

class ClientTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Client';

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
			'clients' => array(
				'client' => array(
					array(
						'client_id' => 13,
						'first_name' => 'Test',
						'last_name' => 'Person',
					),
					array(
						'client_id' => 14,
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
				'Client' => array(
					'client_id' => 13,
					'first_name' => 'Test',
					'last_name' => 'Person',
				),
			),
			1 => array(
				'Client' => array(
					'client_id' => 14,
					'first_name' => 'Testy',
					'last_name' => 'Persony',
				),
			),
		));
		unset($xml, $node, $res);
		
		// TEST GET
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'Client' => array(
				'client_id' => 13,
				'first_name' => 'Test',
				'last_name' => 'Person',
			),
		), array('format' => 'tags'));
		$xml->first()->append($node->children);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$res = $this->Model->findById(13);
		$this->assertEqual($res, array(
			'Client' => array(
				'client_id' => 13,
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
			'first_name' => 'Test',
			'last_name' => 'Person',
			'organization' => 'Test Inc.',
			'email' => 'test@example.com',
			'username' => 'testperson',
		);
		
		// TEST CREATE
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'client_id' => 13,
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
 * end
 */
	public function end() {
		unset($this->Ds);
		unset($this->Model);
		Cache::clear(false, 'freshbooks');
	}

}