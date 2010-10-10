<?php
/**
 * System Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class System extends FreshbooksAppModel {
	public $name = 'System';
	public $displayField = 'company_name';

/**
 * current
 * Return the current user's details.
 * @return array
 */
	public function current() {
		if (!class_exists('Xml')) {
			App::import('Core', 'Xml');
		}
		$xml =& new Xml(array('request' => array('method' => 'system.current')));
		$res = $this->freshbooks($xml->toString(array('header' => true)));
		if ($res === false) {
			return false;
		}
		return array($this->alias => current(Set::extract('/Response/'.$this->alias.'/.', $res)));
	}
}