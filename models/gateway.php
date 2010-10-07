<?php
/**
 * Gateway Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class Gateway extends FreshbooksAppModel {
	public $name = 'Gateway';
	public $displayField = 'name';
	public $schema = array(
		'gateway_id' => array(
			'type' => 'integer',
			'null' => true,
			'key' => 'primary',
			'length' => 10,
		),
		'autobill_capable' => array(
			'type' => 'integer',
			'null' => true,
			'default' => 1,
			'length' => 1,
		),
	);
	public $validate = array(
		
	);

}