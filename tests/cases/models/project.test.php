<?php
/**
 * Project Test
 * 
 * @package freshcake
 * @author Kyle Robinson Young <kyle at kyletyoung.com>
 */
App::import('Model', array('ConnectionManager', 'Freshbooks.Project'));
App::import('Core', array('HttpSocket', 'Xml'));
App::import('Helper', 'Xml');
Mock::generate('HttpSocket');

class ProjectTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Project';

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
			'projects' => array(
				'project' => array(
					array(
						'project_id' => 13,
						'name' => 'Test',
						'description' => 'This is a test.',
						'rate' => 99,
						'client_id' => 13,
					),
					array(
						'project_id' => 14,
						'name' => 'Testy',
						'description' => 'This is a test.',
						'rate' => 99,
						'client_id' => 13,
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
				'Project' => array(
					'project_id' => 13,
					'name' => 'Test',
					'description' => 'This is a test.',
					'rate' => 99,
					'client_id' => 13,
				),
			),
			1 => array(
				'Project' => array(
					'project_id' => 14,
					'name' => 'Testy',
					'description' => 'This is a test.',
					'rate' => 99,
					'client_id' => 13,
				),
			),
		));
		unset($xml, $node, $res);
		
		// TEST GET
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'project' => array(
				'project_id' => 13,
				'name' => 'Test',
				'description' => 'This is a test.',
				'rate' => 99,
				'client_id' => 13,
			),
		), array('format' => 'tags'));
		$xml->first()->append($node->children);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$res = $this->Model->findById(13);
		$this->assertEqual($res, array(
			'Project' => array(
				'project_id' => 13,
				'name' => 'Test',
				'description' => 'This is a test.',
				'rate' => 99,
				'client_id' => 13,
			),
		));
		unset($xml, $node, $res);
		
	}

/**
 * testSave
 */
	public function testSave() {
		$save_data = array(
			'name' => 'Test',
			'description' => 'This is a test.',
			'rate' => 99,
			'hour_budget' => 99,
			'client_id' => 13,
		);
		
		// TEST CREATE
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'project_id' => 13,
		), array('format' => 'tags'));
		$xml->first()->append($node->children);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->assertTrue($this->Model->save($save_data));
		$this->assertEqual($this->Model->id, 13);
		
		$expected = $xml->header()."\n".'<request method="project.update"><project><name><![CDATA[Test]]></name><description><![CDATA[This is a test.]]></description><rate>99</rate><hour_budget>99</hour_budget><client_id>13</client_id><project_id>13</project_id></project></request>';
		$this->assertEqual($this->Model->requestxml(), $expected);
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