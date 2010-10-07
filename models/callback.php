<?php
/**
 * Callback Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class Callback extends FreshbooksAppModel {
	public $name = 'Callback';
	public $displayField = 'event';
	public $schema = array(
		'callback_id' => array(
			'type' => 'integer',
			'null' => true,
			'key' => 'primary',
			'length' => 10,
		),
		'event' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		'uri' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
	);
	public $validate = array(
		'event' => 'notEmpty',
		'uri' => 'notEmpty',
	);
	
	// TODO: Add verify
	// TODO: Add resendToken
}