<?php
/**
 * Client Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class Client extends FreshbooksAppModel {
	public $name = 'Client';
	public $displayField = 'username';
	public $cache = array('duration' => '+30 days');
	public $schema = array(
		'client_id' => array(
			'type' => 'integer',
			'null' => true,
			'key' => 'primary',
			'length' => 10,
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
		'email' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'username' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		/*'password' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),*/
		'work_phone' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'home_phone' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'mobile' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'fax' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'language' => array(
			'type' => 'string',
			'null' => true,
			'length' => 2,
		),
		'currency_code' => array(
			'type' => 'string',
			'null' => true,
			'length' => 3,
		),
		'notes' => array(
			'type' => 'text',
			'null' => true,
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
		's_street1' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		's_street2' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		's_city' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		's_state' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		's_country' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		's_code' => array(
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
	);
	public $validate = array(
		'first_name' => 'notEmpty',
		'last_name' => 'notEmpty',
		'organization' => 'notEmpty',
		'email' => 'email',
	);
}