<?php
/**
 * Examples
 * This will likely be deleted 
 * when examples get added to readme.
 * 
 * @package freshcake
 * @author Kyle Robinson Young
 *
 */
class ExamplesController extends FreshbooksAppController {
	var $uses = array('Freshbooks.TimeEntry');
	var $autoRender = false;
/**
 * index
 */
	/*function index() {
		try {
			
			// FIND TIME ENTRIES
			$entries = $this->TimeEntry->find('all', array(
				'conditions' => array(
					'date_from' => '2010-09-01',
					'date_to' => '2010-09-15',
				),
				'limit' => 15,
			));
			
			// FIND SINGLE ENTRY BY ID
			$entry = $this->TimeEntry->findById(211);
			
			// OR
			
			$entry = $this->TimeEntry->find('first', array(
				'conditions' => array(
					'time_entry_id' => 211,
				),
			));
			
			// FIND TOTAL ENTRIES
			$count = $this->TimeEntry->find('count');
			
			// CREATE ENTRY
			$this->TimeEntry->create();
			$this->TimeEntry->save(array(
				'project_id' => 2,
				'task_id' => 1,
				'notes' => 'DELETE ME',
			));
			
			// UPDATE ENTRY
			$this->TimeEntry->id = 211;
			$this->TimeEntry->save(array(
				//'time_entry_id' => 836, // OR PUT ID HERE
				'project_id' => 2,
				'task_id' => 1,
				'notes' => 'NO REALLY DELETE ME',
			));
			
			// DELETE ENTRY
			$this->TimeEntry->delete(211);
			
		} catch (Exception $e) {
			debug($e->getMessage());
		}
	}*/
}