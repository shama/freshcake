<?php
/**
 * Task Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class Task extends FreshbooksAppModel {
	public $name = 'Task';
	public $displayField = 'name';
	public $cache = array('duration' => '+30 days');
	public $schema = array(
		'task_id' => array(
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
		'billable' => array(
			'type' => 'integer',
			'null' => true,
			'default' => 0,
			'length' => 1,
		),
		'rate' => array(
			'type' => 'decimal',
			'null' => true,
			'length' => '10,2',
		),
		'description' => array(
			'type' => 'text',
			'null' => true,
		),
	);
	public $validate = array(
		'name' => 'notEmpty',
	);

}