<?php
App::uses('BcCrudAppController', 'BcCrud.Controller');

class BcCrudController extends BcCrudAppController {

	/**
	 * モデル
	 *
	 * @var array
	 */
	public $uses = [
		'BcCrud.BcCrudPost',
		'BcCrud.BcCrudCategory',
		'BcCrud.BcCrudTag',
		'BcCrud.BcCrudContent',
		'Content',
	];

	/**
	 * ヘルパー
	 *
	 * @var array
	 */
	public $helpers = [
		'BcText',
		'BcTime',
		'BcFreeze',
		'BcArray',
		'Paginator',
		'BcCrud.BcCrud',
	];

	/**
	 * コンポーネント
	 *
	 * @var array
	 */
	public $components = [
		'BcAuth',
		'Cookie',
		'BcAuthConfigure',
		'RequestHandler',
		'BcEmail',
		'Security',
		'BcContents' => [
			'type' => 'BcCrud.BcCrudContent',
			'useViewCache' => false,
		]
	];

	public $bcCrudContent = [];

	/**
	 * beforeFilter
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		/* 認証設定 */
		$this->BcAuth->allow([
			'index', 'detail', 'category', 'tags'
		]);

		$bcCrudContentId = null;

		if (!empty($this->request->params['entityId'])) {
			$bcCrudContentId = $this->request->params['entityId'];
		}

		if (!$bcCrudContentId) {
			$this->notFound();
		}

		$this->BcCrudContent->recursive = -1;
		$this->bcCrudContent = $this->BcCrudContent->read(null, $bcCrudContentId);

		if (empty($this->request->params['Content'])) {
			$content = $this->BcContents->getContent($bcCrudContentId);
			if($content) {
				$this->request->params['Content'] = $content['Content'];
				$this->request->params['Site'] = $content['Site'];
			}
		}

		// ページネーションのリンク対策
		if (!isset($this->request->params['admin'])) {
			if(!empty($this->request->params['Content'])) {
				$this->passedArgs['controller'] = $this->request->params['Content']['name'];
				$this->passedArgs['plugin'] = $this->request->params['Content']['name'];
			}
			$this->passedArgs['action'] = $this->action;
		}

		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;
	}

	/**
	 * beforeRender
	 *
	 * @return void
	 */
	public function beforeRender() {
		parent::beforeRender();
		$this->set('bcCrudContent', $this->bcCrudContent);

		if (!empty($this->bcCrudContent['BcCrudContent']['widget_area'])) {
			$this->set('widgetArea', $this->bcCrudContent['BcCrudContent']['widget_area']);
		}
	}

	/**
	 * [PUBLIC] 一覧表示
	 *
	 * @return void
	 */
	public function index() {

		if (isset($this->request->params['pass'][0]) && $this->request->params['pass'][0] !== 'index') {
			$this->notFound();
		}

		// プレビュー対応
		if ($this->BcContents->preview == 'default' && $this->request->data) {
			$this->bcCrudContent['BcCrudContent'] = $this->request->data['BcCrudContent'];
			$this->request->data = $this->Content->saveTmpFiles($this->request->data, mt_rand(0, 99999999));
			$this->request->params['Content']['eyecatch'] = $this->request->data['Content']['eyecatch'];
		}

		// 一覧件数取得
		$limit = 9999999;
		if (!empty($this->bcCrudContent['BcCrudContent']['list_count'])) {
			$limit = $this->bcCrudContent['BcCrudContent']['list_count'];
		}

		if (!$this->bcCrudContent['BcCrudContent']['use_category']) {
			$this->BcCrudPost->unbindModel(['belongsTo' => 'BcCrudCategory']);
		}
		if (!$this->bcCrudContent['BcCrudContent']['use_tag']) {
			$this->BcCrudPost->unbindModel(['hasAndBelongsToMany' => 'BcCrudTag']);
		}

		// 一覧取得
		$this->paginate = [
			'conditions' => [
				$this->BcCrudPost->getConditionAllowPublish(),
				'BcCrudPost.bc_crud_content_id' => $this->bcCrudContent['BcCrudContent']['id'],
			],
			'limit' => $limit,
			'order' => [
				'BcCrudPost.sort' => 'DESC',
				'BcCrudPost.id' => 'ASC',
			],
			'cache' => false,
		];
		$datas = $this->paginate('BcCrudPost');
		$this->set('posts', $datas);

		// 管理画面への編集リンク
		$this->set(
			'editLink',
			[
				'admin' => true,
				'plugin' => 'bc_crud',
				'controller' => 'bc_crud_posts',
				'action' => 'index',
				$this->bcCrudContent['BcCrudContent']['id']
			]
		);

		// ページタイトル
		$this->pageTitle = $this->request->params['Content']['title'];

		// Viewファイル
		$template = $this->bcCrudContent['BcCrudContent']['template'] . DS . 'index';
		$this->render($template);
	}

	/**
	 * [PUBLIC] カテゴリ一覧表示
	 *
	 * @return void
	 */
	public function category($name = null) {

		if (empty($name)) {
			$this->notFound();
		}

		if (!$this->bcCrudContent['BcCrudContent']['use_category']) {
			$this->notFound();
		}

		// 一覧件数取得
		$limit = 9999999;
		if (!empty($this->bcCrudContent['BcCrudContent']['list_count'])) {
			$limit = $this->bcCrudContent['BcCrudContent']['list_count'];
		}

		// カテゴリ名からカテゴリ情報取得
		$categoryId = -1;
		$category = $this->BcCrudCategory->find('first', [
			'conditions' => [
				'BcCrudCategory.status' => true,
				'BcCrudCategory.bc_crud_content_id' => $this->bcCrudContent['BcCrudContent']['id'],
				'BcCrudCategory.name' => $name,
			],
			'recursive' => -1,
		]);
		if (isset($category['BcCrudCategory'])) {
			$categoryId = $category['BcCrudCategory']['id'];
		} else {
			$this->notFound();
		}

		// 一覧取得
		$this->paginate = [
			'conditions' => [
				$this->BcCrudPost->getConditionAllowPublish(),
				'BcCrudPost.bc_crud_content_id' => $this->bcCrudContent['BcCrudContent']['id'],
				'BcCrudPost.bc_crud_category_id' => $categoryId
			],
			'limit' => $limit,
			'order' => [
				'BcCrudPost.sort' => 'DESC',
				'BcCrudPost.id' => 'ASC',
			],
			'cache' => false,
		];
		$datas = $this->paginate('BcCrudPost');
		$this->set('posts', $datas);
		$this->set('category', $category);

		// 管理画面への編集リンク
		$this->set(
			'editLink',
			[
				'admin' => true,
				'plugin' => 'bc_crud',
				'controller' => 'bc_crud_categories',
				'action' => 'edit',
				$this->bcCrudContent['BcCrudContent']['id'],
				$categoryId,
			]
		);

		// パンくず
		$this->crumbs[] = [
			'name' => $this->request->params['Content']['title'],
			'url' => $this->request->params['Content']['url']
		];

		// ページタイトル
		$this->pageTitle = $category['BcCrudCategory']['title'];

		// Viewファイル指定
		$template = $this->bcCrudContent['BcCrudContent']['template'] . DS . 'category';
		$this->render($template);
	}

	/**
	 * [PUBLIC] タグ一覧表示
	 *
	 * @return void
	 */
	public function tags($name = null) {

		if (empty($name)) {
			$this->notFound();
		}

		if (!$this->bcCrudContent['BcCrudContent']['use_tag']) {
			$this->notFound();
		}

		// 一覧件数取得
		$limit = 9999999;
		if (!empty($this->bcCrudContent['BcCrudContent']['list_count'])) {
			$limit = $this->bcCrudContent['BcCrudContent']['list_count'];
		}

		// タグ名からタグ情報取得
		$tagId = -1;
		$postIds = -1;
		$tag = $this->BcCrudTag->find('first', [
			'conditions' => [
				'BcCrudTag.status' => true,
				'BcCrudTag.bc_crud_content_id' => $this->bcCrudContent['BcCrudContent']['id'],
				'BcCrudTag.name' => $name,
			],
			'recursive' => 1,
		]);
		if (isset($tag['BcCrudTag'])) {
			$tagId = $tag['BcCrudTag']['id'];
			if (isset($tag['BcCrudPost'][0]['id'])) {
				$postIds = Hash::extract($tag, 'BcCrudPost.{n}.id');
			}
		} else {
			$this->notFound();
		}

		// 一覧取得
		$this->paginate = [
			'conditions' => [
				$this->BcCrudPost->getConditionAllowPublish(),
				'BcCrudPost.bc_crud_content_id' => $this->bcCrudContent['BcCrudContent']['id'],
				'BcCrudPost.id' => $postIds,
			],
			'limit' => $limit,
			'order' => [
				'BcCrudPost.sort' => 'DESC',
				'BcCrudPost.id' => 'ASC',
			],
			'cache' => false,
		];
		$datas = $this->paginate('BcCrudPost');
		$this->set('posts', $datas);
		$this->set('tag', $tag);

		// 管理画面への編集リンク
		$this->set(
			'editLink',
			[
				'admin' => true,
				'plugin' => 'bc_crud',
				'controller' => 'bc_crud_tags',
				'action' => 'edit',
				$this->bcCrudContent['BcCrudContent']['id'],
				$tagId,
			]
		);

		// パンくず
		$this->crumbs[] = [
			'name' => $this->request->params['Content']['title'],
			'url' => $this->request->params['Content']['url']
		];

		// ページタイトル
		$this->pageTitle = $tag['BcCrudTag']['title'];

		// Viewファイル
		$template = $this->bcCrudContent['BcCrudContent']['template'] . DS . 'tags';
		$this->render($template);
	}

	/**
	 * [PUBLIC] 詳細表示
	 *
	 * @return void
	 */
	public function detail($no = null) {

		$post = [];

		if (!$this->bcCrudContent['BcCrudContent']['use_category']) {
			$this->BcCrudPost->unbindModel(['belongsTo' => 'BcCrudCategory']);
		}
		if (!$this->bcCrudContent['BcCrudContent']['use_tag']) {
			$this->BcCrudPost->unbindModel(['hasAndBelongsToMany' => 'BcCrudTag']);
		}

		if ($this->BcContents->preview) {
			// プレビュー対応
			if (!empty($this->request->data['BcCrudPost'])) {

				$this->request->data = $this->BcCrudPost->saveTmpFiles($this->request->data, mt_rand(0, 99999999));
				$post = $this->BcCrudPost->createPreviewData($this->request->data);

			} else {
				$post = $this->BcCrudPost->find('first', [
					'conditions' => [
						'BcCrudPost.bc_crud_content_id' => $this->bcCrudContent['BcCrudContent']['id'],
						'BcCrudPost.no' => $no,
					],
					'recursive' => 1,
				]);
				if (isset($post)) {
					if ($this->BcContents->preview == 'draft') {
						$post['BcCrudPost']['detail'] = $post['BcCrudPost']['detail_draft'];
					}
				}
			}

		} else {

			if (empty($no)) {
				$this->notFound();
			}

			// 詳細情報取得
			$post = $this->BcCrudPost->find('first', [
				'conditions' => [
					$this->BcCrudPost->getConditionAllowPublish(),
					'BcCrudPost.bc_crud_content_id' => $this->bcCrudContent['BcCrudContent']['id'],
					'BcCrudPost.no' => $no,
				],
				'recursive' => 1,
			]);

			// 管理画面への編集リンク
			$this->set(
				'editLink',
				[
					'admin' => true,
					'plugin' => 'bc_crud',
					'controller' => 'bc_crud_posts',
					'action' => 'edit',
					$this->bcCrudContent['BcCrudContent']['id'],
					$post['BcCrudPost']['id'],
				]
			);

		}
		$this->set('post', $post);

		// パンくず
		$this->crumbs[] = [
			'name' => $this->request->params['Content']['title'],
			'url' => $this->request->params['Content']['url']
		];

		// ページタイトル
		$this->pageTitle = $post['BcCrudPost']['name'];

		// Viewファイル
		$template = $this->bcCrudContent['BcCrudContent']['template'] . DS . 'detail';
		$this->render($template);
	}

}
