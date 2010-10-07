<?php
/**
 * Invoice Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
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
			'null' => true,
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
		
		// TODO: Implment Lines on Invoice
	);
	public $validate = array(
		
	);
	
	// TODO: sendByEmail
	// TODO: sendBySnailMail

}