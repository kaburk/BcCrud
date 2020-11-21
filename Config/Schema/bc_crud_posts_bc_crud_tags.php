<?php
class BcCrudPostsBcCrudTagsSchema extends CakeSchema {

	public $file = 'bc_crud_posts_bc_crud_tags.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $bc_crud_posts_bc_crud_tags = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'bc_crud_post_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'bc_crud_tag_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
	);

}
