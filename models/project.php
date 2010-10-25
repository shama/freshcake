<?php
/**
 * Project Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
App::import('Core', 'Xml');
class Project extends FreshbooksAppModel {
	public $name = 'Project';
	public $displayField = 'name';
	public $cache = array('duration' => '+30 days');
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
		'hour_budget' => array(
			'type' => 'decimal',
			'null' => true,
			'length' => '10,2',
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

/**
 * beforeSave
 * Set tasks to xml as the datasource 
 * cant handle it automatically
 * 
 * @return boolean
 */
	public function beforeSave() {
		parent::beforeSave();
		if (isset($this->data[$this->name]['tasks'])) {
			$tasks =& new Xml(
				array('tasks' => $this->data[$this->name]['tasks']),
				array('format' => 'tags')
			);
			$this->data[$this->name]['tasks'] = $tasks->toString();
		}
		return true;
	}
}