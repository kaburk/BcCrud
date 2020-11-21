<?php
class BcCrudContentsSchema extends CakeSchema {

	public $file = 'bc_crud_contents.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $bc_crud_contents = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'template' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20),
		'list_count' => array('type' => 'integer', 'null' => true, 'default' => '10', 'length' => 4, 'unsigned' => false),
		'use_category' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'use_tag' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'use_content' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'widget_area' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
	);

}
