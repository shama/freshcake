<?php
/**
 * Staff Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
Inflector::rules('plural', array('irregular' => array('staff' => 'staff_members')));
class Staff extends FreshbooksAppModel {
	public $name = 'Staff';
	public $displayField = 'username';
	public $cache = array('duration' => '+30 days');

/**
 * current
 * Return the current user's details.
 * @return array
 */
	public function current() {
		if (!class_exists('Xml')) {
			App::import('Core', 'Xml');
		}
		$xml =& new Xml(array('request' => array('method' => 'staff.current')));
		$res = $this->freshbooks($xml->toString(array('header' => true)));
		if ($res === false) {
			return false;
		}
		return array($this->alias => current(Set::extract('/Response/'.$this->alias.'/.', $res)));
	}
}