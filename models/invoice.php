<?php
/**
 * Invoice Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
App::import('Core', 'Xml');
class Invoice extends FreshbooksAppModel {
	public $name = 'Invoice';
	public $displayField = 'number';
	public $schema = array(
		'invoice_id' => array(
			'type' => 'integer',
			'null' => true,
			'key' => 'primary',
			'length' => 10,
		),
		'client_id' => array(
			'type' => 'integer',
			'null' => false,
			'length' => 10,
		),
		'number' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'status' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'date' => array(
			'type' => 'date',
			'null' => true,
		),
		'po_number' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 10,
		),
		'discount' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 10,
		),
		'notes' => array(
			'type' => 'text',
			'null' => true,
		),
		'currency_code' => array(
			'type' => 'string',
			'null' => true,
			'length' => 3,
		),
		'language' => array(
			'type' => 'string',
			'null' => true,
			'length' => 2,
		),
		'terms' => array(
			'type' => 'text',
			'null' => true,
		),
		'return_uri' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'first_name' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'last_name' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'organization' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'p_street1' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'p_street2' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'p_city' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'p_state' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'p_country' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'p_code' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'vat_name' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'vat_number' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'lines' => array(
			'type' => 'text',
			'null' => true,
		),
	);
	public $validate = array(
		'client_id' => 'numeric',
	);

/**
 * saveLines
 * Will either add or update lines or both.
 * 
 * @param array $data
 * @return boolean
 */
	public function saveLines($data=null) {
		if (!isset($data['invoice_id'])) {
			return false;
		}
		if (empty($data['lines'])) {
			return false;
		}
		$update_xml = $add_xml = null;
		foreach ($data['lines'] as $line) {
			$line = array('line' => $line);
			$lines = array(array('invoice_id' => $data['invoice_id']), array('lines' => array()));
			if (isset($line['line']['line_id'])) {
				if (!isset($update_xml)) {
					$update_xml =& new Xml(array('request' => array('method' => 'invoice.lines.update')));
					$update_xml->first()->append($lines, array('format' => 'tags'));
				}
				$update_xml->first()->child('lines')->append($line, array('format' => 'tags'));
			} else {
				if (!isset($add_xml)) {
					$add_xml =& new Xml(array('request' => array('method' => 'invoice.lines.add')));
					$add_xml->first()->append($lines, array('format' => 'tags'));
				}
				$add_xml->first()->child('lines')->append($line, array('format' => 'tags'));
			}
		}
		if (isset($update_xml)) {
			$this->freshbooks($update_xml->toString(array('header' => true)));
		}
		if (isset($add_xml)) {
			$this->freshbooks($add_xml->toString(array('header' => true)));
		}
		return true;
	}

/**
 * deleteLine
 * Deletes a single line from an existing invoice
 * 
 * @param array $data
 * @return boolean
 */
	public function deleteLine($data=null) {
		if (!isset($data['invoice_id']) || !isset($data['line_id'])) {
			return false;
		}
		$xml =& new Xml(array('request' => array('method' => 'invoice.lines.delete')));
		$xml->first()->append($data, array('format' => 'tags'));
		$res = $this->freshbooks($xml->toString(array('header' => true)));
		if ($res === false) {
			return false;
		}
		return true;
	}

/**
 * sendByEmail
 * @param array $data
 * @return boolean
 */
	public function sendByEmail($data=null) {
		$xml =& new Xml(array('request' => array('method' => 'invoice.sendByEmail')));
		$node =& new Xml($data, array('format' => 'tags'));
		$xml->first()->append($node->children);
		$res = $this->freshbooks($xml->toString(array('header' => true)));
		if ($res === false) {
			return false;
		}
		return true;
	}

/**
 * sendBySnailMail
 * @param array $data
 * @return boolean
 */
	public function sendBySnailMail($data=null) {
		$xml =& new Xml(array('request' => array('method' => 'invoice.sendBySnailMail')));
		$node =& new Xml($data, array('format' => 'tags'));
		$xml->first()->append($node->children);
		$res = $this->freshbooks($xml->toString(array('header' => true)));
		if ($res === false) {
			return false;
		}
		return true;
	}
}