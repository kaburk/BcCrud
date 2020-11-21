<?php
class BcCrudCategoriesSchema extends CakeSchema {

	public $file = 'bc_crud_categories.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $bc_crud_categories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'bc_crud_content_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'no' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'title' => array('type' => 'text', 'null' => true, 'default' => null),
		'eye_catch' => array('type' => 'text', 'null' => true, 'default' => null),
		'description' => array('type' => 'text', 'null' => true, 'default' => null),
		'status' => array('type' => 'boolean', 'null' => true, 'default' => false),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'sort' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
	);

}
