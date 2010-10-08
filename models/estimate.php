<?php
/**
 * Estimate Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class Estimate extends FreshbooksAppModel {
	public $name = 'Estimate';
	public $displayField = 'po_number';
	public $schema = array(
		'estimate_id' => array(
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
			'default' => '',
			'length' => 255,
		),
		'p_street2' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		'p_city' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		'p_state' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		'p_country' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		'p_code' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		'vat_name' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		'vat_number' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
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

	// TODO: sendByEmail
}