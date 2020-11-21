<?php
class BcCrudPost extends BcCrudAppModel {

	/**
	 * Behavior
	 *
	 * @var array
	 */
	public $actsAs = [
		'BcCache',
		'BcSearchIndexManager',
		'BcUpload' => [
			'saveDir' => 'bc_crud_post',
			'fields' => [
				'eye_catch' => [
					'type' => 'image',
					'namefield' => 'id',
					'nameadd' => true,
					'nameformat' => '%08d',
					'imageresize' => [
						'width' => '1280',
						'height' => '1280',
					],
					'imagecopy' => [
						'thumb' => [
							'suffix' => '_thumb',
							'width' => '240',
							'height' => '240',
						],
					],
				],
			],
		],
	];

	/**
	 * belongsTo
	 *
	 * @var array
	 */
	public $belongsTo = [
		'BcCrudContent' => [
			'className' => 'BcCrud.BcCrudContent',
		],
		'BcCrudCategory' => [
			'className' => 'BcCrud.BcCrudCategory',
			'conditions' => [
				'BcCrudCategory.status' => true,
			],
			'order' => [
				'BcCrudCategory.sort' => 'ASC',
				'BcCrudCategory.id' => 'ASC',
			],
		],
	];

	/**
	 * hasAndBelongsToMany
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = [
		'BcCrudTag' => [
			'className' => 'BcCrud.BcCrudTag',
			'conditions' => [
				'BcCrudTag.status' => true,
			],
			'order' => [
				'BcCrudTag.sort' => 'ASC',
				'BcCrudTag.id' => 'ASC',
			],
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

		$this->statuses = [
			0 => __d('baser', '非公開'),
			1 => __d('baser', '公開'),
		];

		$this->validate = [
			'name' => [
				[
					'rule' => ['notBlank'],
					'message' => __d('baser', 'タイトルを入力してください。'),
					'required' => true,
				],
				[
					'rule' => ['maxLength', 255],
					'message' => __d('baser', 'タイトルは255文字以内で入力してください。'),
				],
			],
			'status' => [
				[
					'rule' => ['notBlank'],
					'message' => __d('baser', '公開状態を選択してください。'),
				],
			],
			'user_id' => [
				[
					'rule' => ['notBlank'],
					'message' => __d('baser', '作成者を選択してください。'),
				],
			],
			'eye_catch' => [
				[
					'rule' => ['fileCheck', $this->convertSize(ini_get('upload_max_filesize'))],
					'message' => __d('baser', 'ファイルのアップロードに失敗しました。'),
				],
				[
					'rule' => ['fileExt', ['gif', 'jpg', 'jpeg', 'jpe', 'jfif', 'png']],
					'allowEmpty' => true,
					'message' => __d('baser', '許可されていないファイルです。'),
				],
			]
		];
	}

	/**
	 * Default Value
	 *
	 * @param int $bcCrudContentId
	 * @return array
	 */
	public function getDefaultValue($bcCrudContentId) {

		$user = BcUtil::loginUser();
		$data[$this->alias] = [
			'no' => $this->getMax('no', [$this->alias . '.bc_crud_content_id' => $bcCrudContentId]) + 1,
			'posts_date' => date('Y-m-d H:i:s'),
			'status' => false,
			'user_id' => $user['id'],
			'sort' => $this->getMax('sort', [$this->alias . '.bc_crud_content_id' => $bcCrudContentId]) + 1,
		];

		return $data;
	}


	/**
	 * afterSave
	 *
	 * @param boolean $created
	 * @param array $options
	 */
	public function afterSave($created, $options = []) {
		// 検索用テーブルへの登録・削除
		if ($this->searchIndexSaving && !$this->data[$this->alias]['exclude_search']) {
			$this->saveSearchIndex($this->createSearchIndex($this->data));
		} else {
			if (!empty($this->data[$this->alias]['id'])) {
				$this->deleteSearchIndex($this->data[$this->alias]['id']);
			} elseif (!empty($this->id)) {
				$this->deleteSearchIndex($this->id);
			} else {
				$this->cakeError('Not found pk-value in BcCrudPost.');
			}
		}
	}

	/**
	 * beforeDelete
	 *
	 * @return boolean
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
		if (isset($data[$this->alias])) {
			$data = $data[$this->alias];
		}
		$content = $this->BcCrudContent->Content->findByType('BcCrud.BcCrudContent', $data['bc_crud_content_id']);
		if(!$content) {
			return false;
		}

		$status = $data['status'];
		$publishBegin = $data['publish_begin'];
		$publishEnd = $data['publish_end'];

		// コンテンツのステータスを優先する
		if(!$content['Content']['status']) {
			$status = false;
		}

		if($publishBegin) {
			if((!empty($content['Content']['publish_begin']) && $content['Content']['publish_begin'] > $publishBegin)) {
				// コンテンツの公開開始の方が遅い場合
				$publishBegin = $content['Content']['publish_begin'];
			} elseif(!empty($content['Content']['publish_end']) && $content['Content']['publish_end'] < $publishBegin) {
				// 記事の公開開始より、コンテンツの公開終了が早い場合
				$publishBegin = $content['Content']['publish_end'];
			}
		} else {
			if(!empty($content['Content']['publish_begin'])) {
				// 記事の公開開始が定められていない
				$publishBegin = $content['Content']['publish_begin'];
			}
		}
		if($publishEnd) {
			if(!empty($content['Content']['publish_end']) && $content['Content']['publish_end'] < $publishEnd) {
				// コンテンツの公開終了の方が早い場合
				$publishEnd = $content['Content']['publish_end'];
			} elseif(!empty($content['Content']['publish_begin']) && $content['Content']['publish_begin'] < $publishEnd) {
				// 記事の公開終了より、コンテンツの公開開始が早い場合
				$publishEnd = $content['Content']['publish_begin'];
			}
		} else {
			if(!empty($content['Content']['publish_end'])) {
				// 記事の公開終了が定められていない
				$publishEnd = $content['Content']['publish_end'];
			}
		}

		return ['SearchIndex' => [
			'type' => __d('baser', 'BcCrud'),
			'model_id' => $this->id,
			'content_filter_id' => !empty($data['bc_crud_category_id']) ? $data['bc_crud_category_id'] : '',
			'content_id' => $content['Content']['id'],
			'site_id' => $content['Content']['site_id'],
			'title' => $data['name'],
			'detail' => $data['content'] . ' ' . $data['detail'],
			'url' => $content['Content']['url'] . 'detail/' . $data['no'],
			'status' => $status,
			'publish_begin' => $publishBegin,
			'publish_end' => $publishEnd
		]];
	}

	/**
	 * コンテンツをコピーする
	 *
	 * @param int $id
	 * @param array $data
	 * @return mixed page Or false
	 */
	public function copy($id = null, $data = []) {
		if ($id) {
			$data = $this->find('first', ['conditions' => [$this->alias . '.id' => $id]]);
		}
		$oldData = $data;

		// EVENT BcCrudPost.beforeCopy
		$event = $this->dispatchEvent('beforeCopy', [
			'data' => $data,
			'id' => $id,
		]);
		if ($event !== false) {
			$data = $event->result === true ? $event->data['data'] : $event->result;
		}

		$user = BcUtil::loginUser();
		$data[$this->alias]['name'] .= '_copy';
		$data[$this->alias]['no'] = $this->getMax('no', [$this->alias . '.bc_crud_content_id' => $data[$this->alias]['bc_crud_content_id']]) + 1;
		$data[$this->alias]['status'] = false;
		$data[$this->alias]['posts_date'] = date('Y-m-d H:i:s');
		$data[$this->alias]['sort'] = $this->getMax('sort', [$this->alias  . '.bc_crud_content_id' => $data[$this->alias]['bc_crud_content_id']]) + 1;
		$data[$this->alias]['user_id'] = $user['id'];

		unset($data[$this->alias]['id']);
		unset($data[$this->alias]['created']);
		unset($data[$this->alias]['modified']);

		// 一旦退避(afterSaveでリネームされてしまうのを避ける為）
		$eyeCatch = $data[$this->alias]['eye_catch'];
		unset($data[$this->alias]['eye_catch']);

		if (!empty($data['BcCrudTag'])) {
			foreach ($data['BcCrudTag'] as $key => $tag) {
				$data['BcCrudTag'][$key] = $tag['id'];
			}
		}

		$this->create($data);
		$result = $this->save();

		if ($result) {
			if ($eyeCatch) {
				$result[$this->alias]['eye_catch'] = $eyeCatch;
				$this->set($result);
				$result = $this->renameToBasenameFields(true);
				$this->set($result);	// 内部でリネームされたデータが再セットされる
				$result = $this->save();
			}
			// EVENT BcCrudPost.afterCopy
			$this->dispatchEvent('afterCopy', [
				'id' => $result[$this->alias]['id'],
				'data' => $result,
				'oldId' => $id,
				'oldData' => $oldData,
			]);
			return $result;
		} else {
			if (isset($this->validationErrors['name'])) {
				return $this->copy(null, $data);
			} else {
				return false;
			}
		}
	}

	/**
	 * プレビュー用のデータを生成する
	 *
	 * @param array $data
	 */
	public function createPreviewData($data) {
		$post[$this->alias] = $data[$this->alias];
		if(isset($post[$this->alias]['detail_tmp'])) {
			$post[$this->alias]['detail'] = $post[$this->alias]['detail_tmp'];
		}

		if ($data[$this->alias]['bc_crud_category_id']) {
			$category = $this->BcCrudCategory->find('first', [
				'conditions' => ['BcCrudCategory.id' => $data[$this->alias]['bc_crud_category_id']],
				'recursive' => -1
			]);
			$post['BcCrudCategory'] = $category['BcCrudCategory'];
		}

		if ($data[$this->alias]['user_id']) {
			$author = $this->User->find('first', [
				'conditions' => ['User.id' => $data[$this->alias]['user_id']],
				'recursive' => -1
			]);
			$post['User'] = $author['User'];
		}

		if (!empty($data['BcCrudTag']['BcCrudTag'])) {
			$tags = $this->BcCrudTag->find('all', [
				'conditions' => ['BcCrudTag.id' => $data['BcCrudTag']['BcCrudTag']],
				'recursive' => -1
			]);
			if ($tags) {
				$tags = Hash::extract($tags, '{n}.BcCrudTag');
				$post['BcCrudTag'] = $tags;
			}
		}

		unset($data[$this->alias]);
		unset($data['BcCrudTag']); // プレビュー時に、フロントでの利用データの形式と異なるため削除
		$post = Hash::merge($data, $post);

		return $post;
	}

	/**
	 * 公開状態を取得する
	 *
	 * @param array $data モデルデータ
	 * @return boolean 公開状態
	 */
	public function allowPublish($data) {

		if (isset($data[$this->alias])) {
			$data = $data[$this->alias];
		}

		$allowPublish = (int)$data['status'];

		if ($data['publish_begin'] == '0000-00-00 00:00:00') {
			$data['publish_begin'] = null;
		}
		if ($data['publish_end'] == '0000-00-00 00:00:00') {
			$data['publish_end'] = null;
		}

		// 期限を設定している場合に条件に該当しない場合は強制的に非公開とする
		if (($data['publish_begin'] && $data['publish_begin'] >= date('Y-m-d H:i:s')) ||
			($data['publish_end'] && $data['publish_end'] <= date('Y-m-d H:i:s'))) {
			$allowPublish = false;
		}

		return $allowPublish;
	}

}
