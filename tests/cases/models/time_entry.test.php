<?php
/**
 * TimeEntry Test
 * 
 * @package freshcake
 * @author Kyle Robinson Young <kyle at kyletyoung.com>
 */
App::import('Model', array('ConnectionManager', 'Freshbooks.TimeEntry'));
App::import('Core', array('HttpSocket', 'Xml'));
App::import('Helper', 'Xml');
Mock::generate('HttpSocket');

class TimeEntryTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'TimeEntry';

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
			'time_entries' => array(
				'time_entry' => array(
					array(
						'time_entry_id' => 13,
						'staff_id' => 13,
						'project_id' => 13,
						'task_id' => 13,
						'hours' => 99,
						'date' => '2009-03-13',
						'notes' => 'This is a test.',
					),
					array(
						'time_entry_id' => 14,
						'staff_id' => 13,
						'project_id' => 13,
						'task_id' => 13,
						'hours' => 99,
						'date' => '2009-03-13',
						'notes' => 'This is a test.',
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
				'TimeEntry' => array(
					'time_entry_id' => 13,
					'staff_id' => 13,
					'project_id' => 13,
					'task_id' => 13,
					'hours' => 99,
					'date' => '2009-03-13',
					'notes' => 'This is a test.',
				),
			),
			1 => array(
				'TimeEntry' => array(
					'time_entry_id' => 14,
					'staff_id' => 13,
					'project_id' => 13,
					'task_id' => 13,
					'hours' => 99,
					'date' => '2009-03-13',
					'notes' => 'This is a test.',
				),
			),
		));
		unset($xml, $node, $res);
		
		// TEST GET
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'time_entry' => array(
				'time_entry_id' => 13,
				'staff_id' => 13,
				'project_id' => 13,
				'task_id' => 13,
				'hours' => 99,
				'date' => '2009-03-13',
				'notes' => 'This is a test.',
			),
		), array('format' => 'tags'));
		$xml->first()->append($node->children);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$res = $this->Model->findById(13);
		$this->assertEqual($res, array(
			'TimeEntry' => array(
				'time_entry_id' => 13,
				'staff_id' => 13,
				'project_id' => 13,
				'task_id' => 13,
				'hours' => 99,
				'date' => '2009-03-13',
				'notes' => 'This is a test.',
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
			'project_id' => 13,
			'task_id' => 13,
			'hours' => 99,
			'date' => '2009-03-13',
			'notes' => 'This is a test.',
		);
		
		// TEST CREATE
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'time_entry_id' => 13,
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