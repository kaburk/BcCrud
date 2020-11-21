<?php
class BcCrudPostsSchema extends CakeSchema {

	public $file = 'bc_crud_posts.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $bc_crud_posts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'bc_crud_content_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'bc_crud_category_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'no' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'name' => array('type' => 'text', 'null' => true, 'default' => null),
		'eye_catch' => array('type' => 'text', 'null' => true, 'default' => null),
		'content' => array('type' => 'text', 'null' => true, 'default' => null),
		'detail' => array('type' => 'text', 'null' => true, 'default' => null),
		'content_draft' => array('type' => 'text', 'null' => true, 'default' => null),
		'detail_draft' => array('type' => 'text', 'null' => true, 'default' => null),
		'posts_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'status' => array('type' => 'boolean', 'null' => true, 'default' => false),
		'publish_begin' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'publish_end' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'sort' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'exclude_search' => array('type' => 'boolean', 'null' => true, 'default' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
	);

}
