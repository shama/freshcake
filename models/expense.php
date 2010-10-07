<?php
/**
 * Expense Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class Expense extends FreshbooksAppModel {
	public $name = 'Expense';
	public $displayField = 'notes';
	public $schema = array(
		'expense_id' => array(
			'type' => 'integer',
			'null' => true,
			'key' => 'primary',
			'length' => 10,
		),
		'staff_id' => array(
			'type' => 'integer',
			'null' => false,
			'length' => 10,
		),
		'category_id' => array(
			'type' => 'integer',
			'null' => false,
			'length' => 10,
		),
		'project_id' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 10,
		),
		'client_id' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 10,
		),
		'amount' => array(
			'type' => 'decimal',
			'null' => true,
			'length' => '10,2',
		),
		'vendor' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'date' => array(
			'type' => 'date',
			'null' => true,
		),
		'notes' => array(
			'type' => 'text',
			'null' => true,
		),
		'status' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 10,
		),
		'tax1_name' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'tax1_percent' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 10,
		),
		'tax1_amount' => array(
			'type' => 'decimal',
			'null' => true,
			'length' => '10,2',
		),
		'tax2_name' => array(
			'type' => 'string',
			'null' => true,
			'length' => 255,
		),
		'tax2_percent' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 10,
		),
		'tax2_amount' => array(
			'type' => 'decimal',
			'null' => true,
			'length' => '10,2',
		),
	);
	public $validate = array(
		'staff_id' => 'numeric',
		'category_id' => 'numeric',
	);

}