<?php
/**
 * Callback Model
 * 
 * @package freshcake
 * @author Kyle Robinson Young, kyletyoung.com
 */
class Callback extends FreshbooksAppModel {
	public $name = 'Callback';
	public $displayField = 'event';
	public $schema = array(
		'callback_id' => array(
			'type' => 'integer',
			'null' => true,
			'key' => 'primary',
			'length' => 10,
		),
		'event' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
		'uri' => array(
			'type' => 'string',
			'null' => true,
			'default' => '',
			'length' => 255,
		),
	);
	public $validate = array(
		'event' => 'notEmpty',
		'uri' => 'notEmpty',
	);

/**
 * verify
 * @param array $data
 * @return boolean
 */
	public function verify($data=null) {
		if (!class_exists('Xml')) {
			App::import('Core', 'Xml');
		}
		$xml =& new Xml(array('request' => array('method' => 'callback.verify')));
		$node =& new Xml(array('callback' => $data), array('format' => 'tags'));
		$xml->first()->append($node->children);
		$res = $this->freshbooks($xml->toString(array('header' => true)));
		if ($res === false) {
			return false;
		}
		return true;
	}

/**
 * resendToken
 * @param array $data
 * @return boolean
 */
	public function resendToken($data=null) {
		if (!class_exists('Xml')) {
			App::import('Core', 'Xml');
		}
		$xml =& new Xml(array('request' => array('method' => 'callback.resendToken')));
		$node =& new Xml(array('callback' => $data), array('format' => 'tags'));
		$xml->first()->append($node->children);
		$res = $this->freshbooks($xml->toString(array('header' => true)));
		if ($res === false) {
			return false;
		}
		return true;
	}
}