<?php
/**
 * Gateway Test
 * 
 * @package freshcake
 * @author Kyle Robinson Young <kyle at kyletyoung.com> 
 */
App::import('Model', array('ConnectionManager', 'Freshbooks.Gateway'));
App::import('Core', array('HttpSocket', 'Xml'));
App::import('Helper', 'Xml');
Mock::generate('HttpSocket');

class GatewayTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Gateway';
	
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
			'gateways' => array(
				'gateway' => array(
					array(
						'name' => 'Authorize.net',
						'autobill_capable' => 1,
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
				'Gateway' => array(
					'name' => 'Authorize.net',
					'autobill_capable' => 1,
				),
			),
		));
		unset($xml, $node, $res);
	}

/**
 * end
 */
	public function end() {
		unset($this->Ds);
		unset($this->Model);
	}
}