<?php
/**
 * Recurring Test
 * 
 * @package freshcake
 * @author Kyle Robinson Young <kyle at kyletyoung.com>
 * 
 * TODO:
 * 	Add tests for lines and autobill
 */
App::import('Model', array('ConnectionManager', 'Freshbooks.Recurring'));
App::import('Core', array('HttpSocket', 'Xml'));
App::import('Helper', 'Xml');
Mock::generate('HttpSocket');

class RecurringTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Recurring';

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
			'recurrings' => array(
				'recurring' => array(
					array(
						'recurring_id' => 13,
						'frequency' => 'm',
						'occurrences' => 0,
						'stopped' => 1,
						'client_id' => 13,
						'organization' => 'Test Inc.',
						'first_name' => 'Test',
						'last_name' => 'Person',
						'autobill' => array(
							'gateway' => 'Authorize.net',
							'card' => array(
								'number' => '4111 1111 1111 1111',
								'name' => 'Test Person',
								'expiration' => array(
									'month' => 3,
									'year' => 2010,
								),
							),
						),
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
						'recurring_id' => 14,
						'frequency' => 'm',
						'occurrences' => 0,
						'stopped' => 1,
						'client_id' => 13,
						'organization' => 'Test Inc.',
						'first_name' => 'Test',
						'last_name' => 'Person',
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
				'Recurring' => array(
					'recurring_id' => 13,
					'frequency' => 'm',
					'occurrences' => 0,
					'stopped' => 1,
					'client_id' => 13,
					'organization' => 'Test Inc.',
					'first_name' => 'Test',
					'last_name' => 'Person',
					'autobill' => array(
						'gateway' => 'Authorize.net',
						'card' => array(
							'number' => '4111 1111 1111 1111',
							'name' => 'Test Person',
							'expiration' => array(
								'month' => 3,
								'year' => 2010,
							),
						),
					),
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
				'Recurring' => array(
					'recurring_id' => 14,
					'frequency' => 'm',
					'occurrences' => 0,
					'stopped' => 1,
					'client_id' => 13,
					'organization' => 'Test Inc.',
					'first_name' => 'Test',
					'last_name' => 'Person',
				),
			),
		));
		unset($xml, $node, $res);
		
		// TEST GET
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'recurring' => array(
				'recurring_id' => 13,
				'frequency' => 'm',
				'occurrences' => 0,
				'stopped' => 1,
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
			'Recurring' => array(
				'recurring_id' => 13,
				'frequency' => 'm',
				'occurrences' => 0,
				'stopped' => 1,
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
			'frequency' => 'm',
			'occurrences' => 0,
			'stopped' => 1,
			'client_id' => 13,
			'organization' => 'Test Inc.',
			'first_name' => 'Test',
			'last_name' => 'Person',
		);
		
		// TEST CREATE
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'recurring_id' => 13,
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
		unset($xml);

		// TEST AUTOBILL & LINES
		$xml =& new Xml($this->successXml);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->assertTrue($this->Model->save(array(
			'client_id' => 13,
			'date' => '2010-09-09',
			'po_number' => '1234',
			'discount' => 10,
			'occurrences' => 1,
			'frequency' => 'monthly',
			'send_email' => 1,
			'send_snail_mail' => 0,
			'autobill' => array(
				'gateway_name' => 'Authorize.net',
				'card' => array(
					'number' => '4111 1111 1111 1111',
					'name' => 'Test Person',
					'expiration' => array(
						'month' => 3,
						'year' => 2099,
					),
				),
			),
			'lines' => array(
				array(
					'name' => 'Yard Work',
					'description' => 'Mowed the lawn.',
					'unit_cost' => 10,
					'quantity' => 4,
					'tax1_name' => 'GST',
					'tax2_name' => 'PST',
					'tax1_percent' => 8,
					'tax2_percent' => 6,
					'type' => 'Time',
				),
				array(
					'name' => 'More Yard Work',
					'description' => 'Mowed the lawn.',
					'unit_cost' => 10,
					'quantity' => 4,
					'tax1_name' => 'GST',
					'tax2_name' => 'PST',
					'tax1_percent' => 8,
					'tax2_percent' => 6,
					'type' => 'Time',
				),
			),
		)));
		$expected = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
		$expected .= '<request method="recurring.update"><recurring><client_id>13</client_id><date><![CDATA[2010-09-09]]></date><po_number>1234</po_number><discount>10</discount><occurrences>1</occurrences><frequency><![CDATA[monthly]]></frequency><send_email>1</send_email><send_snail_mail>0</send_snail_mail><autobill><gateway_name><![CDATA[Authorize.net]]></gateway_name><card><number><![CDATA[4111 1111 1111 1111]]></number><name><![CDATA[Test Person]]></name><expiration><month>3</month><year>2099</year></expiration></card></autobill><lines><line><name><![CDATA[Yard Work]]></name><description><![CDATA[Mowed the lawn.]]></description><unit_cost>10</unit_cost><quantity>4</quantity><tax1_name><![CDATA[GST]]></tax1_name><tax2_name><![CDATA[PST]]></tax2_name><tax1_percent>8</tax1_percent><tax2_percent>6</tax2_percent><type><![CDATA[Time]]></type></line><line><name><![CDATA[More Yard Work]]></name><description><![CDATA[Mowed the lawn.]]></description><unit_cost>10</unit_cost><quantity>4</quantity><tax1_name><![CDATA[GST]]></tax1_name><tax2_name><![CDATA[PST]]></tax2_name><tax1_percent>8</tax1_percent><tax2_percent>6</tax2_percent><type><![CDATA[Time]]></type></line></lines><recurring_id>13</recurring_id></recurring></request>';
		$this->assertEqual($this->Model->requestXml(), $expected);
	}

/**
 * testLines
 */
	public function testLines() {
		// recurring.lines.add
		$save_data = array(
			'recurring_id' => 13,
			'lines' => array(
				array(
					'amount' => 99,
					'name' => 'Yak Shaving',
					'description' => 'Shaved the yak',
					'unit_cost' => 40,
					'quantity' => 4,
					'type' => 'Item',
				),
				array(
					'amount' => 99,
					'name' => 'Yak Shaving',
					'description' => 'Shaved the yak',
					'unit_cost' => 40,
					'quantity' => 4,
					'type' => 'Item',
				),
			),
		);
		$xml =& new Xml($this->successXml);
		$node =& new Xml(array(
			'recurring_id' => 13,
			'lines' => array(
				array('line_id' => 45),
				array('line_id' => 46),
			),
		), array('format' => 'tags'));
		$xml->first()->append($node->children);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->assertTrue($this->Model->saveLines($save_data));
		
		$expected = $xml->header()."\n".'<request method="recurring.lines.add"><recurring_id>13</recurring_id><lines><line><amount>99</amount><name><![CDATA[Yak Shaving]]></name><description><![CDATA[Shaved the yak]]></description><unit_cost>40</unit_cost><quantity>4</quantity><type><![CDATA[Item]]></type></line><line><amount>99</amount><name><![CDATA[Yak Shaving]]></name><description><![CDATA[Shaved the yak]]></description><unit_cost>40</unit_cost><quantity>4</quantity><type><![CDATA[Item]]></type></line></lines></request>';
		$this->assertEqual($this->Model->requestXml(), $expected);
		unset($xml, $node);
		
		// recurring.lines.update
		$save_data = array(
			'recurring_id' => 13,
			'lines' => array(
				array(
					'line_id' => 45,
					'amount' => 99,
					'name' => 'Yak Shaving',
					'description' => 'Shaved the yak',
					'unit_cost' => 40,
					'quantity' => 4,
					'type' => 'Item',
				),
				array(
					'line_id' => 46,
					'amount' => 99,
					'name' => 'Yak Shaving',
					'description' => 'Shaved the yak',
					'unit_cost' => 40,
					'quantity' => 4,
					'type' => 'Item',
				),
			),
		);
		$xml =& new Xml($this->successXml);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->assertTrue($this->Model->saveLines($save_data));
		
		$expected = $xml->header()."\n".'<request method="recurring.lines.update"><recurring_id>13</recurring_id><lines><line><line_id>45</line_id><amount>99</amount><name><![CDATA[Yak Shaving]]></name><description><![CDATA[Shaved the yak]]></description><unit_cost>40</unit_cost><quantity>4</quantity><type><![CDATA[Item]]></type></line><line><line_id>46</line_id><amount>99</amount><name><![CDATA[Yak Shaving]]></name><description><![CDATA[Shaved the yak]]></description><unit_cost>40</unit_cost><quantity>4</quantity><type><![CDATA[Item]]></type></line></lines></request>';
		$this->assertEqual($this->Model->requestXml(), $expected);
		unset($xml, $node);
		
		// recurring.lines.delete
		$delete_data = array(
			'recurring_id' => 13,
			'line_id' => 45,
		);
		$xml =& new Xml($this->successXml);
		
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $xml->toString());
		
		$this->assertTrue($this->Model->deleteLine($delete_data));
		
		$expected = $xml->header()."\n".'<request method="recurring.lines.delete"><recurring_id>13</recurring_id><line_id>45</line_id></request>';
		$this->assertEqual($this->Model->requestXml(), $expected);
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
 * end
 */
	public function end() {
		unset($this->Ds);
		unset($this->Model);
		Cache::clear(false, 'freshbooks');
	}

}