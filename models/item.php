<?php
/**
 * Item Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class Item extends FreshbooksAppModel {
	public $name = 'Item';
	public $displayField = 'name';
	public $cache = array('duration' => '+30 days');
	public $schema = array(
		'item_id' => array(
			'type' => 'integer',
			'null' => true,
			'key' => 'primary',
			'length' => 10,
		),
		'name' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'description' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'unit_cost' => array(
			'type' => 'decimal',
			'null' => true,
			'length' => '10,2',
		),
		'quantity' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 10,
		),
		'inventory' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 10,
		),
	);
	public $validate = array(
		'name' => 'notEmpty',
	);

}