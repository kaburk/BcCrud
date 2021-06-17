<?php
/**
 * コンテンツ管理 右クリックメニュー設定
 */
$config['BcContents']['items']['BcCrud'] = [
	'BcCrudContent'	=> [
		'title' =>  __d('baser', 'サンプルプラグイン'),
		'multiple'	=> true,
		'preview'	=> true,
		'icon'	=> 'bca-icon--bc_crud',
		'routes' => [
			'manage'	=> [
				'admin' => true,
				'plugin'	=> 'bc_crud',
				'controller'=> 'bc_crud_posts',
				'action'	=> 'index'
			],
			'add'	=> [
				'admin' => true,
				'plugin'	=> 'bc_crud',
				'controller'=> 'bc_crud_contents',
				'action'	=> 'ajax_add'
			],
			'edit'	=> [
				'admin' => true,
				'plugin'	=> 'bc_crud',
				'controller'=> 'bc_crud_contents',
				'action'	=> 'edit'
			],
			'delete' => [
				'admin' => true,
				'plugin'	=> 'bc_crud',
				'controller'=> 'bc_crud_contents',
				'action'	=> 'delete'
			],
			'view'	=> [
				'admin' => false,
				'plugin'	=> 'bc_crud',
				'controller'=> 'bc_crud',
				'action'	=> 'index'
			],
			'copy'	=> [
				'admin' => true,
				'plugin'	=> 'bc_crud',
				'controller'=> 'bc_crud_contents',
				'action'	=> 'ajax_copy'
			],
			'dblclick'	=> [
				'admin' => true,
				'plugin'	=> 'bc_crud',
				'controller'=> 'bc_crud_posts',
				'action'	=> 'index'
			],
		],
	],
];

$Model = ClassRegistry::init('BcCrud.BcCrudContent');
$bcCrudContents = $Model->find('all', [
	'conditions' => [
		$Model->Content->getConditionAllowPublish()
	],
	'recursive' => 0,
	'order' => $Model->id,
]);
if ($bcCrudContents) {
	foreach ($bcCrudContents as $bcCrudContent) {
		$content = $bcCrudContent['Content'];
		$bcCrudContent = $bcCrudContent['BcCrudContent'];
		$config['BcApp.adminNavigation.Contents.' . 'BcCrudContent' . $bcCrudContent['id']] = [
			'siteId' => $content['site_id'],
			'title' => $content['title'],
			'type' => 'bc_crud-content',
			'icon' => 'bca-icon--bc_crud',
			'menus' => [
				'BcCrudPosts' . $bcCrudContent['id'] => [
					'title' => '記事',
					'url' => ['admin' => true, 'plugin' => 'bc_crud', 'controller' => 'bc_crud_posts', 'action' => 'index', $bcCrudContent['id']],
					'currentRegex' => '/\/bc_crud\/bc_crud_posts\/[^\/]+?\/' . $bcCrudContent['id'] . '($|\/)/s'
				],
				'BcCrudCategories' . $bcCrudContent['id'] => [
					'title' => 'カテゴリ',
					'url' => ['admin' => true, 'plugin' => 'bc_crud', 'controller' => 'bc_crud_categories', 'action' => 'index', $bcCrudContent['id']],
					'currentRegex' => '/\/bc_crud\/bc_crud_categories\/[^\/]+?\/' . $bcCrudContent['id'] . '($|\/)/s'
				],
				'BcCrudTags' . $bcCrudContent['id'] => [
					'title' => 'タグ',
					'url' => ['admin' => true, 'plugin' => 'bc_crud', 'controller' => 'bc_crud_tags', 'action' => 'index', $bcCrudContent['id']],
					'currentRegex' => '/\/bc_crud\/bc_crud_tags\/[^\/]+?\/' . $bcCrudContent['id'] . '($|\/)/s'
				],
				'BcCrudContentsEdit' . $bcCrudContent['id'] => [
					'title' => '設定',
					'url' => ['admin' => true, 'plugin' => 'bc_crud', 'controller' => 'bc_crud_contents', 'action' => 'edit', $bcCrudContent['id']]
				]
			]
		];
		if (!$bcCrudContent['use_category']) {
			unset($config['BcApp.adminNavigation.Contents.' . 'BcCrudContent' . $bcCrudContent['id']]['menus']['BcCrudCategories' . $bcCrudContent['id']]);
		}
		if (!$bcCrudContent['use_tag']) {
			unset($config['BcApp.adminNavigation.Contents.' . 'BcCrudContent' . $bcCrudContent['id']]['menus']['BcCrudTags' . $bcCrudContent['id']]);
		}
	}
}
