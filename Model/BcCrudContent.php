<?php
class BcCrudContent extends BcCrudAppModel {

	/**
	 * Behavior
	 *
	 * @var array
	 */
	public $actsAs = [
		'BcCache',
		'BcContents',
		'BcSearchIndexManager',
	];

	/**
	 * hasMany
	 *
	 * @var array
	 */
	public $hasMany = [
		'BcCrudPost' => [
			'className' => 'BcCrud.BcCrudPost',
			'dependent' => true,
			'exclusive' => false,
		],
		'BcCrudCategory' => [
			'className' => 'BcCrud.BcCrudCategory',
			'order' => [
				'BcCrudCategory.sort' => 'ASC',
				'BcCrudCategory.id' => 'ASC',
			],
			'dependent' => true,
			'exclusive' => false,
		],
	];

	/**
	 * constructor.
	 *
	 * @param bool $id
	 * @param null $table
	 * @param null $ds
	 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->validate = [
			'list_count' => [
				[
					'rule' => 'halfText',
					'message' => __d('baser', '一覧表示件数は半角で入力してください。'),
					'allowEmpty' => false,
				],
				[
					'rule' => ['range', 0, 101],
					'message' => __d('baser', '一覧表示件数は100までの数値で入力してください。'),
				],
			],
		];
	}

	/**
	 * Default Value
	 *
	 * @param var
	 * @return array
	 */
	public function getDefaultValue() {

		$data[$this->alias] = [
			'list_count' => 10,
			'feed_count' => 10,
			'use_tag' => true,
			'use_content' => true,
		];

		return $data;
	}

	/**
	 * afterSave
	 *
	 * @return boolean
	 */
	public function afterSave($created, $options = []) {

		if (empty($this->data[$this->alias]['id'])) {
			$this->data[$this->alias]['id'] = $this->getInsertID();
		}

		// 検索用テーブルへの登録・削除
		if (!$this->data['Content']['exclude_search'] && $this->data['Content']['status']) {
			$this->saveSearchIndex($this->createSearchIndex($this->data));
			clearDataCache();
			$datas = $this->BcCrudPost->find('all', [
				'conditions' => ['BcCrudPost.bc_crud_content_id' => $this->data[$this->alias]['id']],
				'recursive' => -1
			]);
			foreach($datas as $data) {
				$this->BcCrudPost->set($data);
				$this->BcCrudPost->afterSave(true);
			}
		} else {
			$this->deleteSearchIndex($this->data[$this->alias]['id']);
		}
	}

	/**
	 * beforeDelete
	 *
	 * @return	boolean
	 * @access	public
	 */
	public function beforeDelete($cascade = true) {
		// 検索用データを削除
		return $this->deleteSearchIndex($this->id);
	}

	/**
	 * 検索用データを生成する
	 *
	 * @param array $data
	 * @return array|false
	 */
	public function createSearchIndex($data) {

		if (!isset($data[$this->alias]) || !isset($data['Content'])) {
			return false;
		}

		$bcCrudContent = $data[$this->alias];
		$content = $data['Content'];
		return [
			'SearchIndex' => [
				'type' => 'BcCrud',
				'model_id' => (!empty($bcCrudContent['id'])) ? $bcCrudContent['id'] : $this->id,
				'content_id'=> $content['id'],
				'site_id'=> $content['site_id'],
				'title' => $content['title'],
				'detail' => $content['description'],
				'url' => $content['url'],
				'status' => $content['status'],
				'publish_begin' => $content['publish_begin'],
				'publish_end' => $content['publish_end'],
			],
		];
	}

	/**
	 * コンテンツをコピーする
	 *
	 * @param int $id ページID
	 * @param int $newParentId 新しい親コンテンツID
	 * @param string $newTitle 新しいタイトル
	 * @param int $newAuthorId 新しいユーザーID
	 * @param int $newSiteId 新しいサイトID
	 * @return mixed bcCrudContent|false
	 */
	public function copy($id, $newParentId, $newTitle, $newAuthorId, $newSiteId = null) {

		$data = $this->find('first', ['conditions' => [$this->alias . '.id' => $id], 'recursive' => 0]);
		$oldData = $data;

		// EVENT BcCrudContent.beforeCopy
		$event = $this->dispatchEvent('beforeCopy', [
			'data' => $data,
			'id' => $id,
		]);
		if ($event !== false) {
			$data = $event->result === true ? $event->data['data'] : $event->result;
		}

		$url = $data['Content']['url'];
		$siteId = $data['Content']['site_id'];
		$name = $data['Content']['name'];
		$eyeCatch = $data['Content']['eyecatch'];
		unset($data[$this->alias]['id']);
		unset($data[$this->alias]['created']);
		unset($data[$this->alias]['modified']);
		unset($data['Content']);
		$data['Content'] = [
			'name'		=> $name,
			'parent_id'	=> $newParentId,
			'title'		=> $newTitle,
			'author_id' => $newAuthorId,
			'site_id' 	=> $newSiteId,
			'exclude_search' => false
		];
		if(!is_null($newSiteId) && $siteId != $newSiteId) {
			$data['Content']['site_id'] = $newSiteId;
			$data['Content']['parent_id'] = $this->Content->copyContentFolderPath($url, $newSiteId);
		}

		$this->getDataSource()->begin();

		$this->create($data);
		if ($result = $this->save()) {
			$result[$this->alias]['id'] = $this->getInsertID();
			$data = $result;

			$BcCrudPosts = $this->BcCrudPost->find('all', [
				'conditions' => [
					'BcCrudPost.bc_crud_content_id' => $id
				],
				'order' => [
					'BcCrudPost.id' => 'ASC',
				],
				'recursive' => -1,
			]);
			foreach ($BcCrudPosts as $BcCrudPost) {
				$BcCrudPost['BcCrudPost']['bc_crud_content_id'] = $result[$this->alias]['id'];
				$this->BcCrudPost->copy(null, $BcCrudPost);
			}
			if ($eyeCatch) {
				$result['Content']['id'] = $this->Content->getLastInsertID();
				$result['Content']['eyecatch'] = $eyeCatch;
				$this->Content->set(['Content' => $result['Content']]);
				$result = $this->Content->renameToBasenameFields(true);
				$this->Content->set($result);
				$result = $this->Content->save();
				$data['Content'] = $result['Content'];
			}

			// EVENT BcCrudContent.afterCopy
			$event = $this->dispatchEvent('afterCopy', [
				'id' => $data[$this->alias]['id'],
				'data' => $data,
				'oldId' => $id,
				'oldData' => $oldData,
			]);

			$this->getDataSource()->commit();
			return $result;
		}
		$this->getDataSource()->rollback();
		return false;
	}

}
