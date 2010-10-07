<?php
/**
 * Freshbooks App Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class FreshbooksAppModel extends AppModel {

/**
 * useDbConfig
 * @var string
 */
	public $useDbConfig = 'freshbooks';

/**
 * schema
 * Instead of _schema, the freshbooks datasource
 * will set the schema dynamically.
 * 
 * @var array
 */
	public $schema = null;

/**
 * __construct
 * Defaults required parameters if not set in local model.
 * 
 * @param array $id
 * @param string $table
 * @param string $ds
 */
	public function __construct($id=null, $table=null, $ds=null) {
		if (!isset($this->primaryKey)) {
			$this->primaryKey = Inflector::underscore($id['alias']).'_id';
		}
		if (!isset($this->useTable)) {
			$this->useTable = Inflector::underscore(Inflector::pluralize($id['alias']));
		}
		if (!isset($this->schema)) {
			$this->schema = array(
				$this->primaryKey => array(
					'type' => 'integer',
					'null' => true,
					'key' => 'primary',
					'length' => 10,
				),
			);
		}
		parent::__construct($id, $table, $ds);
	}
}