<?php
/**
 * Time Entry Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class TimeEntry extends FreshbooksAppModel {
	public $name = 'TimeEntry';
	public $displayField = 'notes';
	public $schema = array(
		'time_entry_id' => array(
			'type' => 'integer',
			'null' => true,
			'key' => 'primary',
			'length' => 10,
		),
		'project_id' => array(
			'type' => 'integer',
			'null' => true,
			'default' => '',
			'length' => 10,
		),
		'task_id' => array(
			'type' => 'integer',
			'null' => true,
			'default' => '',
			'length' => 10,
		),
		'staff_id' => array(
			'type' => 'integer',
			'null' => true,
			'default' => '',
			'length' => 10,
		),
		'hours' => array(
			'type' => 'decimal',
			'null' => true,
			'default' => '',
			'length' => '10,2',
		),
		'notes' => array(
			'type' => 'text',
			'null' => true,
			'default' => '',
		),
		'date' => array(
			'type' => 'date',
			'null' => true,
			'default' => '',
		),
	);
	public $validate = array(
		'project_id' => 'numeric',
		'task_id' => 'numeric',
	);
}