<?php
/**
 * Project Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class Project extends FreshbooksAppModel {
	public $name = 'Project';
	public $displayField = 'name';
	public $schema = array(
		'project_id' => array(
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
		'bill_method' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'client_id' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 10,
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
		'tasks' => array(
			'type' => 'text',
			'null' => true,
		),
	);
	public $validate = array(
		'name' => 'notEmpty',
		'billMethod' => 'notEmpty',
	);
}