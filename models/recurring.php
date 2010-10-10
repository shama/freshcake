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
}