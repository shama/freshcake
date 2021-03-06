<?php
/**
 * Expense Test
 * 
 * @package freshcake
 * @author Kyle Robinson Young <kyle at kyletyoung.com> 
 */
App::import('Model', array('ConnectionManager', 'Freshbooks.Expense'));
App::import('Core', array('HttpSocket', 'Xml'));
App::import('Helper', 'Xml');
Mock::generate('HttpSocket');

class ExpenseTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Expense';

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
			'expenses' => array(
				'expense' => array(
					array(
						'expense_id' => 13,
						'staff_id' => 13,
						'category_id' => 13,
						'project_id' => 13,
						'client_id' => 13,
						'amount' => 99.99,
						'notes' => 'Test',
					),
					array(
						'expense_id' => 14,
						'staff_id' => 13,
						'category_id' => 13,
						'project_id' => 13,
						'client_id' => 13,
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
				'Expense' => array(
					'expense_id' => 13,
					'staff_id' => 13,
					'category_id' => 13,
					'project_id' => 13,
					'client_id' => 13,
					'amount' => 99.99,
					'notes' => 'Test',
				),
			),
			1 => array(
				'Expense' => array(
					'expense_id' => 14,
					'staff_id' => 13,
					'category_id' => 13,
					'project_id' => 13,
					'client_id' => 13,
					'amount' => 99.99,
					'notes' => 'Test',
				),
			),
		));
		unset($xml, $node, $res);
		
		// TEST GET
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'expense' => array(
				'expense_id' => 13,
				'staff_id' => 13,
				'category_id' => 13,
				'project_id' => 13,
				'client_id' => 13,
				'amount' => 99.99,
				'notes' => 'Test',
			),
		), array('format' => 'tags'));
		$xml->first()->append($node->children);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$res = $this->Model->findById(13);
		$this->assertEqual($res, array(
			'Expense' => array(
				'expense_id' => 13,
				'staff_id' => 13,
				'category_id' => 13,
				'project_id' => 13,
				'client_id' => 13,
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
			'staff_id' => 13,
			'category_id' => 13,
			'project_id' => 13,
			'client_id' => 13,
			'amount' => 99.99,
			'notes' => 'Test',
		);
		
		// TEST CREATE
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'expense_id' => 13,
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