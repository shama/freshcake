<?php
/**
 * Recurring Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class Recurring extends FreshbooksAppModel {
	public $name = 'Recurring';
	public $displayField = 'po_number';
	public $schema = array(
		'recurring_id' => array(
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
		'date' => array(
			'type' => 'date',
			'null' => false,
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
		'occurrences' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 10,
		),
		'frequency' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'send_email' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 1,
		),
		'send_snail_mail' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 1,
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
		'notes' => array(
			'type' => 'text',
			'null' => true,
		),
		'terms' => array(
			'type' => 'text',
			'null' => true,
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
		'return_uri' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'lines' => array(
			'type' => 'text',
			'null' => true,
		),
		'autobill' => array(
			'type' => 'text',
			'null' => true,
		),
	);
	public $validate = array(
		'client_id' => 'numeric',
		'date' => 'date',
	);

/**
 * beforeSave
 * Set autobill to xml as the datasource 
 * cant handle it automatically
 * 
 * @return boolean
 */
	public function beforeSave() {
		parent::beforeSave();
		if (isset($this->data[$this->name]['autobill'])) {
			$tasks =& new Xml(
				array('autobill' => $this->data[$this->name]['autobill']),
				array('format' => 'tags')
			);
			$this->data[$this->name]['autobill'] = $tasks->toString();
		}
		return true;
	}

/**
 * saveLines
 * Will either add or update lines or both.
 * 
 * @param array $data
 * @return boolean
 */
	public function saveLines($data=null) {
		if (!isset($data['recurring_id'])) {
			return false;
		}
		if (empty($data['lines'])) {
			return false;
		}
		$update_xml = $add_xml = null;
		foreach ($data['lines'] as $line) {
			$line = array('line' => $line);
			$lines = array(array('recurring_id' => $data['recurring_id']), array('lines' => array()));
			if (isset($line['line']['line_id'])) {
				if (!isset($update_xml)) {
					$update_xml =& new Xml(array('request' => array('method' => 'recurring.lines.update')));
					$update_xml->first()->append($lines, array('format' => 'tags'));
				}
				$update_xml->first()->child('lines')->append($line, array('format' => 'tags'));
			} else {
				if (!isset($add_xml)) {
					$add_xml =& new Xml(array('request' => array('method' => 'recurring.lines.add')));
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
		if (!isset($data['recurring_id']) || !isset($data['line_id'])) {
			return false;
		}
		$xml =& new Xml(array('request' => array('method' => 'recurring.lines.delete')));
		$xml->first()->append($data, array('format' => 'tags'));
		$res = $this->freshbooks($xml->toString(array('header' => true)));
		if ($res === false) {
			return false;
		}
		return true;
	}
}