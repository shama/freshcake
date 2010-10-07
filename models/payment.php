<?php
/**
 * Payment Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class Payment extends FreshbooksAppModel {
	public $name = 'Payment';
	public $displayField = 'notes';
	public $schema = array(
		'payment_id' => array(
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
		'invoice_id' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 10,
		),
		'date' => array(
			'type' => 'date',
			'null' => true,
		),
		'amount' => array(
			'type' => 'decimal',
			'null' => true,
			'length' => '10,2',
		),
		'currency_code' => array(
			'type' => 'string',
			'null' => true,
			'length' => 3,
		),
		'type' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'notes' => array(
			'type' => 'text',
			'null' => true,
		),
	);
	public $validate = array(
		'client_id' => 'numeric',
	);

}