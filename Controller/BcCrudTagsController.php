<?php
class BcCrudTagsController extends BcCrudAppController {

	/**
	 * モデル
	 *
	 * @var array
	 */
	public $uses = [
		'BcCrud.BcCrudTag',
		'BcCrud.BcCrudContent',
		'User',
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
		'BcContents' => [
			'type' => 'BcCrud.BcCrudContent',
		],
	];

	/**
	 * BcCrudコンテンツデータ
	 *
	 * @var array
	 */
	public $bcCrudContent = null;

	/**
	 * beforeFilter
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		if (isset($this->request->params['pass'][0])) {
			$content = $this->BcContents->getContent($this->request->params['pass'][0]);
			if (!$content) {
				$this->notFound();
			}
			$this->request->params['Content'] = $content['Content'];
			$this->request->params['Site'] = $content['Site'];
			$this->BcCrudContent->recursive = -1;
			$this->bcCrudContent = $this->BcCrudContent->read(null, $this->request->params['pass'][0]);
		}
		if (!empty($this->siteConfigs['editor']) && $this->siteConfigs['editor'] != 'none') {
			$this->helpers[] = $this->siteConfigs['editor'];
		}

		$this->set('bcCrudContent', $this->bcCrudContent);
	}

	/**
	 * beforeRender
	 *
	 * @return void
	 */
	public function beforeRender() {
		parent::beforeRender();
	}

	/**
	 * [ADMIN] 一覧表示
	 *
	 * @param int $bcCrudContentId
	 * @return void
	 */
	public function admin_index($bcCrudContentId) {

		if (!$bcCrudContentId || !$this->bcCrudContent) {
			$this->BcMessage->setError(__d('baser', '無効な処理です。'));
			$this->redirect(['plugin' => false, 'admin' => true, 'controller' => 'contents', 'action' => 'index']);
		}

		$default = ['named' => [
			'num' => $this->siteConfigs['admin_list_num'],
			'sort' => 'id',
			'direction' => 'desc',
		]];
		$this->setViewConditions($this->modelClass, ['group' => $bcCrudContentId, 'default' => $default]);

		$conditions = $this->_createAdminIndexConditions($bcCrudContentId, $this->request->data);

		if (strpos($this->passedArgs['sort'], '.') === false) {
			$order = $this->modelClass . '.' . $this->passedArgs['sort'];
		}
		if ($order && $this->passedArgs['direction']) {
			$order .= ' ' . $this->passedArgs['direction'];
		}

		$options = [
			'fields' => [],
			'conditions' => $conditions,
			'order' => $order,
			'limit' => $this->passedArgs['num'],
			'recursive' => -1,
			'cache' => false,
		];

		$this->paginate = $options;
		$posts = $this->paginate($this->modelClass);
		$this->set('posts', $posts);

		$this->_setAdminIndexViewData($bcCrudContentId);

		if ($this->request->is('ajax') || !empty($this->request->query['ajax'])) {
			$this->render('ajax_index');
			return;
		}

		$this->pageTitle = sprintf(__d('baser', '%s｜タグ一覧'), strip_tags($this->request->params['Content']['title']));
		$this->search = 'bc_crud_tags_index';
		$this->help = 'bc_crud_tags_index';
	}

	/**
	 * [ADMIN] 一覧の表示用データをセットする
	 *
	 * @return void
	 */
	protected function _setAdminIndexViewData($bcCrudContentId) {

		$this->set('users', $this->User->getUserList());
		$this->set('statuses', $this->{$this->modelClass}->statuses);
	}

	/**
	 * [ADMIN] ページ一覧用の検索条件を生成する
	 *
	 * @param int $bcCrudContentId
	 * @return array $conditions
	 */
	protected function _createAdminIndexConditions($bcCrudContentId, $data) {

		unset($data['_Token']);
		unset($data['ListTool']);

		// 条件指定のないフィールドを解除
		if (!empty($data[$this->modelClass])) {
			foreach ($data[$this->modelClass] as $key => $value) {
				if (trim($value) === '') {
					unset($data[$this->modelClass][$key]);
				}
			}
		}

		$conditions = [$this->modelClass . '.bc_crud_content_id' => $bcCrudContentId];

		if (isset($data[$this->modelClass]['name'])) {
			$conditions[] = [
				'OR' => [
					[$this->modelClass . '.name LIKE' ] => '%' . h(trim($data[$this->modelClass]['name'])) . '%',
					[$this->modelClass . '.title LIKE'] => '%' . h(trim($data[$this->modelClass]['name'])) . '%',
				]
			];
		}

		if (isset($data[$this->modelClass]['user_id'])) {
			$conditions[$this->modelClass . '.user_id'] = $data[$this->modelClass]['user_id'];
		}

		if (isset($data[$this->modelClass]['status'])) {
			$conditions[$this->modelClass . '.status'] = $data[$this->modelClass]['status'];
		}

		return $conditions;
	}

	/**
	 * [ADMIN] 登録処理
	 *
	 * @param int $bcCrudContentId
	 * @return void
	 */
	public function admin_add($bcCrudContentId) {

		if (!$bcCrudContentId || !$this->bcCrudContent) {
			$this->BcMessage->setError(__d('baser', '無効な処理です。'));
			$this->redirect(['controller' => 'bc_crud_contents', 'action' => 'index']);
		}

		if ($this->request->is(['post', 'put'])) {

			if ($this->{$this->modelClass}->isOverPostSize()) {
				$this->BcMessage->setError(__d('baser', '送信できるデータ量を超えています。合計で %s 以内のデータを送信してください。', ini_get('post_max_size')));
				$this->redirect(['action' => 'add', $bcCrudContentId]);
			}

			$this->request->data[$this->modelClass]['bc_crud_content_id'] = $bcCrudContentId;
			$this->request->data[$this->modelClass]['no'] = $this->{$this->modelClass}->getMax('no', [$this->modelClass . '.bc_crud_content_id' => $bcCrudContentId]) + 1;

			if (empty($this->request->data[$this->modelClass]['sort'])) {
				$this->request->data[$this->modelClass]['sort'] = $this->{$this->modelClass}->getMax('sort', [$this->modelClass . '.bc_crud_content_id' => $bcCrudContentId]) + 1;
			}

			if (!BcUtil::isAdminUser()) {
				$user = $this->BcAuth->user();
				$this->request->data[$this->modelClass]['user_id'] = $user['id'];
			}

			// EVENT BcCrudCategories.beforeAdd
			$event = $this->dispatchEvent('beforeAdd', [
				'data' => $this->request->data
			]);
			if ($event !== false) {
				$this->request->data = $event->result === true ? $event->data['data'] : $event->result;
			}

			// データを保存
			if ($this->{$this->modelClass}->saveAll($this->request->data)) {
				clearViewCache();
				$id = $this->{$this->modelClass}->getLastInsertId();
				$this->BcMessage->setSuccess(sprintf(__d('baser', 'タグ「%s」を追加しました。'), $this->request->data[$this->modelClass]['title']));
				$this->{$this->modelClass}->recursive = 1;

				// EVENT BcCrudCategories.afterAdd
				$this->dispatchEvent('afterAdd', [
					'data' => $this->{$this->modelClass}->read(null, $id)
				]);

				// 編集画面にリダイレクト
				$this->redirect(['action' => 'edit', $bcCrudContentId, $id]);
			} else {
				$this->BcMessage->setError(__d('baser', 'エラーが発生しました。内容を確認してください。'));
			}
		} else {
			$this->request->data = $this->{$this->modelClass}->getDefaultValue($bcCrudContentId);
		}

		// 表示設定
		$this->_setAdminFormViewData($bcCrudContentId);

		$this->crumbs[] = [
			'name' => sprintf(
						__d('baser', '%s 一覧'),
						$this->request->params['Content']['title']
					),
			'url' => [
				'action' => 'index',
				$bcCrudContentId,
			],
		];

		$this->set('previewId', 'add_' . mt_rand(0, 99999999));

		$this->pageTitle = sprintf(__d('baser', '%s｜新規追加'), $this->request->params['Content']['title']);
		$this->help = 'bc_crud_tags_form';

		$this->render('form');
	}

	/**
	 * [ADMIN] 編集処理
	 *
	 * @param int $bcCrudContentId
	 * @param int $id
	 * @return void
	 */
	public function admin_edit($bcCrudContentId, $id) {

		if (!$bcCrudContentId || !$this->bcCrudContent || !$id) {
			$this->BcMessage->setError(__d('baser', '無効な処理です。'));
			$this->redirect(['controller' => 'bc_crud_contents', 'action' => 'index']);
		}

		$this->{$this->modelClass}->recursive = -1;

		if ($this->request->is(['post', 'put'])) {

			if ($this->{$this->modelClass}->isOverPostSize()) {
				$this->BcMessage->setError(__d('baser', '送信できるデータ量を超えています。合計で %s 以内のデータを送信してください。', ini_get('post_max_size')));
				$this->redirect(['action' => 'edit', $bcCrudContentId, $id]);
			}

			$this->request->data[$this->modelClass]['bc_crud_content_id'] = $bcCrudContentId;

			if (empty($this->request->data[$this->modelClass]['sort'])) {
				$this->request->data[$this->modelClass]['sort'] = $this->{$this->modelClass}->getMax('sort', [$this->modelClass . '.bc_crud_content_id' => $bcCrudContentId]) + 1;
			}

			if (!BcUtil::isAdminUser()) {
				$this->request->data[$this->modelClass]['user_id'] = $this->{$this->modelClass}->field('user_id', [
					$this->modelClass . '.bc_crud_content_id' => $bcCrudContentId,
					$this->modelClass . '.id' => $id,
				]);
			}

			// EVENT BcCrudCategories.beforeEdit
			$event = $this->dispatchEvent('beforeEdit', [
				'data' => $this->request->data
			]);
			if ($event !== false) {
				$this->request->data = $event->result === true ? $event->data['data'] : $event->result;
			}

			// データを保存
			if ($this->{$this->modelClass}->saveAll($this->request->data)) {
				clearViewCache();
				$this->BcMessage->setSuccess(sprintf(__d('baser', 'タグ「%s」を更新しました。'), $this->request->data[$this->modelClass]['title']));

				// EVENT BcCrudCategories.afterEdit
				$this->dispatchEvent('afterEdit', [
					'data' => $this->{$this->modelClass}->read(null, $id)
				]);

				// 編集画面にリダイレクト
				$this->redirect(['action' => 'edit', $bcCrudContentId, $id]);
			} else {
				$this->BcMessage->setError(__d('baser', 'エラーが発生しました。内容を確認してください。'));
			}
		} else {
			$this->request->data = $this->{$this->modelClass}->find('first', [
				'conditions' => [
					$this->modelClass . '.bc_crud_content_id' => $bcCrudContentId,
					$this->modelClass . '.id' => $id,
				],
			]);
			if (!$this->request->data) {
				$this->BcMessage->setError(__d('baser', '無効な処理です。'));
				$this->redirect(['action' => 'index', $bcCrudContentId]);
			}
		}

		// 表示設定
		$this->_setAdminFormViewData($bcCrudContentId);

		if ($this->request->data[$this->modelClass]['status']) {
			$this->set(
				'publishLink',
				$this->Content->getUrl(
					urldecode($this->request->params['Content']['url']) . 'tags/' . $this->request->data[$this->modelClass]['no'],
					true,
					$this->request->params['Site']['use_subdomain']
				)
			);
		}

		$this->crumbs[] = [
			'name' => sprintf(
						__d('baser', '%s 一覧'),
						$this->request->params['Content']['title']
					),
			'url' => [
				'action' => 'index',
				$bcCrudContentId,
			]
		];

		$this->set('previewId', $this->request->data[$this->modelClass]['id']);

		$this->pageTitle = sprintf(__d('baser', '%s｜編集'), $this->request->params['Content']['title']);
		$this->help = 'bc_crud_tags_form';

		$this->render('form');
	}

	/**
	 * [ADMIN] 一覧の表示用データをセットする
	 *
	 * @return void
	 */
	protected function _setAdminFormViewData($bcCrudContentId) {

		$this->set('users', $this->User->getUserList());
		$this->set('statuses', $this->{$this->modelClass}->statuses);
	}

	/**
	 * [ADMIN] 削除処理　(ajax)
	 *
	 * @param int $bcCrudContentId
	 * @param int $id
	 * @return void
	 */
	public function admin_ajax_delete($bcCrudContentId, $id = null) {
		$this->_checkSubmitToken();
		if (!$id) {
			$this->ajaxError(500, __d('baser', '無効な処理です。'));
		}

		// 削除実行
		if ($this->_del($id)) {
			clearViewCache();
			exit(true);
		}

		exit();
	}

	/**
	 * 一括削除
	 *
	 * @param array $ids
	 * @return boolean
	 */
	protected function _batch_del($ids) {

		if ($ids) {
			foreach ($ids as $id) {
				$this->_del($id);
			}
		}

		return true;
	}

	/**
	 * データを削除する
	 *
	 * @param int $id
	 * @return boolean
	 */
	protected function _del($id) {

		// メッセージ用にデータを取得
		$post = $this->{$this->modelClass}->read(null, $id);

		// 削除実行
		if ($this->{$this->modelClass}->delete($id)) {
			$this->BcMessage->setSuccess(sprintf(
					__d('baser', 'タグ「%s」を削除しました。'),
					$post[$this->modelClass]['name']
			));
			return true;
		} else {
			return false;
		}
	}

	/**
	 * [ADMIN] 削除処理
	 *
	 * @param int $bcCrudContentId
	 * @param int $id
	 * @return void
	 */
	public function admin_delete($bcCrudContentId, $id = null) {

		$this->_checkSubmitToken();

		if (!$bcCrudContentId || !$id) {
			$this->BcMessage->setError(__d('baser', '無効な処理です。'));
			$this->redirect(['controller' => 'bc_crud_contents', 'action' => 'index']);
		}

		// メッセージ用にデータを取得
		$post = $this->{$this->modelClass}->read(null, $id);

		// 削除実行
		if ($this->{$this->modelClass}->delete($id)) {
			clearViewCache();
			$this->BcMessage->setSuccess(
				sprintf(
					__d('baser', 'タグ「%s」を削除しました。'),
					$post[$this->modelClass]['name']
				)
			);
		} else {
			$this->BcMessage->setError(__d('baser', 'データベース処理中にエラーが発生しました。'));
		}

		$this->redirect(['action' => 'index', $bcCrudContentId]);
	}


	/**
	 * [ADMIN] 無効状態にする（AJAX）
	 *
	 * @param string $bcCrudContentId
	 * @param string $id
	 * @return void
	 */
	public function admin_ajax_unpublish($bcCrudContentId, $id) {

		$this->_checkSubmitToken();

		if (!$id) {
			$this->ajaxError(500, __d('baser', '無効な処理です。'));
		}
		if ($this->_changeStatus($id, false)) {
			clearViewCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->{$this->modelClass}->validationErrors);
		}

		exit();
	}

	/**
	 * [ADMIN] 有効状態にする（AJAX）
	 *
	 * @param string $bcCrudContentId
	 * @param string $id
	 * @return void
	 */
	public function admin_ajax_publish($bcCrudContentId, $id) {

		$this->_checkSubmitToken();

		if (!$id) {
			$this->ajaxError(500, __d('baser', '無効な処理です。'));
		}
		if ($this->_changeStatus($id, true)) {
			clearViewCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->{$this->modelClass}->validationErrors);
		}

		exit();
	}

	/**
	 * 一括公開
	 *
	 * @param array $ids
	 * @return boolean
	 * @access protected
	 */
	protected function _batch_publish($ids) {

		if ($ids) {
			foreach ($ids as $id) {
				$this->_changeStatus($id, true);
			}
		}

		clearViewCache();

		return true;
	}

	/**
	 * 一括非公開
	 *
	 * @param array $ids
	 * @return boolean
	 * @access protected
	 */
	protected function _batch_unpublish($ids) {

		if ($ids) {
			foreach ($ids as $id) {
				$this->_changeStatus($id, false);
			}
		}

		clearViewCache();

		return true;
	}

	/**
	 * ステータスを変更する
	 *
	 * @param int $id
	 * @param boolean $status
	 * @return boolean
	 */
	protected function _changeStatus($id, $status) {

		$statusTexts = $this->{$this->modelClass}->statuses;
		$data = $this->{$this->modelClass}->find(
			'first',
			[
				'conditions' => [
					$this->modelClass . '.id' => $id,
			],
			'recursive' => -1
		]);

		$data[$this->modelClass]['status'] = $status;
		$data[$this->modelClass]['publish_begin'] = '';
		$data[$this->modelClass]['publish_end'] = '';
		unset($data[$this->modelClass]['eye_catch']);

		$this->{$this->modelClass}->set($data);

		if ($this->{$this->modelClass}->save()) {
			$statusText = $statusTexts[$status];
			$this->{$this->modelClass}->saveDbLog(
				sprintf(
					__d('baser', 'タグ「%s」を %s に設定しました。'),
					$data[$this->modelClass]['name'],
					$statusText
				)
			);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * [ADMIN] コピー
	 *
	 * @param int $bcCrudContentId
	 * @param int $id
	 * @return void
	 */
	public function admin_ajax_copy($bcCrudContentId, $id = null) {

		$this->_checkSubmitToken();

		$result = $this->{$this->modelClass}->copy($id);

		if ($result) {
			// タグ情報を取得するため読み込みなおす
			$this->{$this->modelClass}->recursive = 1;
			$data = $this->{$this->modelClass}->read();
			$this->setViewConditions($this->modelClass, ['action' => 'admin_index']);
			$this->_setAdminIndexViewData($bcCrudContentId);
			$this->set('data', $data);
			$message = sprintf(
				__d('baser', 'コピーしてタグ「%s」を追加しました。'),
				$data[$this->modelClass]['name']
			);
			$this->BcMessage->setSuccess($message, true, false);
		} else {
			$this->ajaxError(500, $this->{$this->modelClass}->validationErrors);
		}
	}

}
