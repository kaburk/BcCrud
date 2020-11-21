<?php
class BcCrudTag extends BcCrudAppModel {

	/**
	 * Behavior
	 *
	 * @var array
	 */
	public $actsAs = [
		'BcCache',
		'BcUpload' => [
			'saveDir' => 'bc_crud_tag',
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
	 * hasAndBelongsToMany
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = [
		'BcCrudPost' => [
			'className' => 'BcCrud.BcCrudPost',
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
					'message' => __d('baser', 'タグ名を入力してください。'),
					'required' => true,
				],
				[
					'rule' => 'alphaNumericDashUnderscore',
					'message' => __d('baser', 'タグ名は半角のみで入力してください。'),
				],
				[
					'rule' => ['isUnique'],
					'message' => __d('baser', '入力されたタグ名は既に登録されています。'),
				],
				[
					'rule' => ['maxLength', 255],
					'message' => __d('baser', 'タグ名は255文字以内で入力してください。'),
				],
			],
			'title' => [
				[
					'rule' => ['notBlank'],
					'message' => __d('baser', 'タグタイトルを入力してください。'),
					'required' => true,
				],
				[
					'rule' => ['maxLength', 255],
					'message' => __d('baser', 'タグタイトルは255文字以内で入力してください。'),
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
	 * @param var
	 * @return array
	 */
	public function getDefaultValue($bcCrudContentId) {

		$user = BcUtil::loginUser();
		$data[$this->alias] = [
			'status' => false,
			'user_id' => $user['id'],
			'sort' => $this->getMax('sort', [$this->alias . '.bc_crud_content_id' => $bcCrudContentId]) + 1,
		];

		return $data;
	}

	/**
	 * 一覧取得
	 *
	 * @param array $bcCrudContentId
	 * @return array
	 */
	public function getList($bcCrudContentId, $conditions = []) {

		$conditions['bc_crud_content_id'] = $bcCrudContentId;
		$conditions['status'] = true;

		$results = $this->find('list', [
			'fields' => [
				'id',
				'title'
			],
			'conditions' => $conditions,
			'order' => [
				'sort' => 'ASC',
				'id' => 'ASC',
			],
		]);

		return $results;
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

		// EVENT BcCrudCategory.beforeCopy
		$event = $this->dispatchEvent('beforeCopy', [
			'data' => $data,
			'id' => $id,
		]);
		if ($event !== false) {
			$data = $event->result === true ? $event->data['data'] : $event->result;
		}

		$user = BcUtil::loginUser();
		$data[$this->alias]['name'] .= '_copy';
		$data[$this->alias]['title'] .= '_copy';
		$data[$this->alias]['no'] = $this->getMax('no', [$this->alias . '.bc_crud_content_id' => $data[$this->alias]['bc_crud_content_id']]) + 1;
		$data[$this->alias]['status'] = false;
		$data[$this->alias]['sort'] = $this->getMax('sort', [$this->alias  . '.bc_crud_content_id' => $data[$this->alias]['bc_crud_content_id']]) + 1;
		$data[$this->alias]['user_id'] = $user['id'];

		unset($data[$this->alias]['id']);
		unset($data[$this->alias]['created']);
		unset($data[$this->alias]['modified']);

		// 一旦退避(afterSaveでリネームされてしまうのを避ける為）
		$eyeCatch = $data[$this->alias]['eye_catch'];
		unset($data[$this->alias]['eye_catch']);

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
			// EVENT BcCrudCategory.afterCopy
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

		return $allowPublish;
	}

}
