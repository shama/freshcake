<?php
/**
 * System Test
 * 
 * @package freshcake
 * @author Kyle Robinson Young <kyle at kyletyoung.com>
 */
App::import('Model', array('ConnectionManager', 'Freshbooks.System'));
App::import('Core', array('HttpSocket', 'Xml'));
App::import('Helper', 'Xml');
Mock::generate('HttpSocket');

class SystemTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'System';

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

	public function testCurrent() {
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'System' => array(
				'company_name' => 'Test Inc',
				'profession' => 'Cake Plugin Builder',
				'address' => array(
					'street1' => '123 Fake St',
					'street2' => '',
					'city' => 'Test',
					'province' => 'BC',
					'postal_code' => '12345',
					'country' => 'Canada',
				),
				'api' => array(
					'requests' => 2,
					'request_limit' => 5000,
				),
			),
		), array('format' => 'tags'));
		$xml->first()->append($node->children);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$res = $this->Model->current();
		$this->assertEqual($res, array(
			'System' => array(
				'company_name' => 'Test Inc',
				'profession' => 'Cake Plugin Builder',
				'address' => array(
					'street1' => '123 Fake St',
					'street2' => '',
					'city' => 'Test',
					'province' => 'BC',
					'postal_code' => '12345',
					'country' => 'Canada',
				),
				'api' => array(
					'requests' => 2,
					'request_limit' => 5000,
				),
			),
		));
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