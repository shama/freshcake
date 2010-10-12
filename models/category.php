<?php
/**
 * Category Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class Category extends FreshbooksAppModel {
	public $name = 'Category';
	public $displayField = 'name';
	public $cache = array('duration' => '+30 days');
	public $schema = array(
		'category_id' => array(
			'type' => 'integer',
			'null' => true,
			'key' => 'primary',
			'length' => 10,
		),
		'name' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
	);
	public $validate = array(
		'name' => 'notEmpty',
	);
}