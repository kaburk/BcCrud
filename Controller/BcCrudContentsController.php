<?php
class BcCrudContentsController extends BcCrudAppController {

	/**
	 * モデル
	 *
	 * @var array
	 */
	public $uses = [
		'BcCrud.BcCrudContent',
		'Content',
		'SiteConfig',
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
			'useForm' => true,
		],
	];

	/**
	 * [ADMIN] コンテンツ登録
	 *
	 * @return mixed json|false
	 */
	public function admin_ajax_add() {

		$this->autoRender = false;
		if (!$this->request->data) {
			$this->ajaxError(500, '無効な処理です。');
		}

		$this->request->data[$this->modelClass] = $this->{$this->modelClass}->getDefaultValue()[$this->modelClass];
		$data = $this->{$this->modelClass}->save($this->request->data);
		if ($data) {
			$message = '「' . $this->request->data['Content']['title'] . '」を追加しました。';
			$this->BcMessage->setSuccess($message, true, false);
			return json_encode($data['Content']);
		} else {
			$this->ajaxError(500, $this->{$this->modelClass}->validationErrors);
		}

		return false;
	}

	/**
	 * [ADMIN] 編集処理
	 *
	 * @param int $id
	 * @return void
	 */
	public function admin_edit($id) {

		if (!$id && empty($this->request->data)) {
			$this->BcMessage->setError(__d('baser', '無効なIDです。'));
			$this->redirect(['plugin' => false, 'admin' => true, 'controller' => 'contents', 'action' => 'index']);
		}

		if ($this->request->is(['post', 'put'])) {
			if ($this->Content->isOverPostSize()) {
				$this->BcMessage->setError(__d('baser', '送信できるデータ量を超えています。合計で %s 以内のデータを送信してください。', ini_get('post_max_size')));
				$this->redirect(['action' => 'edit', $id]);
			}

			$this->{$this->modelClass}->set($this->request->data);
			if ($this->{$this->modelClass}->save()) {
				$this->BcMessage->setSuccess(sprintf(__d('baser', '「%s」を更新しました。'), $this->request->data['Content']['title']));
				$this->redirect(['action' => 'edit', $id]);
			} else {
				$this->BcMessage->setError(__d('baser', '入力エラーです。内容を修正してください。'));
			}
		} else {
			$this->request->data = $this->{$this->modelClass}->read(null, $id);
			if (!$this->request->data) {
				$this->BcMessage->setError(__d('baser', '無効な処理です。'));
				$this->redirect(['plugin' => false, 'admin' => true, 'controller' => 'contents', 'action' => 'index']);
			}
		}
		$site = BcSite::findById($this->request->data['Content']['site_id']);
		if(!empty($this->request->data['Content']['status'])) {
			$this->set('publishLink', $this->Content->getUrl($this->request->data['Content']['url'], true, $site->useSubDomain));
		}
		$this->request->params['Content'] = $this->BcContents->getContent($id)['Content'];
		$this->set('bcCrudContent', $this->request->data);
		$this->subMenuElements = ['bc_crud_posts'];
		$this->set('themes', $this->SiteConfig->getThemes());
		$this->pageTitle = __d('baser', '設定編集');
		$this->help = 'bc_crud_contents_form';
		$this->render('form');
	}

	/**
	 * [ADMIN] 削除（ゴミ箱へ移動）
	 *
	 * Controller::requestAction() で呼び出される
	 *
	 * @return bool
	 */
	public function admin_delete() {

		$id = $this->request->data('entityId');
		if (empty($id)) {
			return false;
		}
		if ($this->{$this->modelClass}->delete($id)) {
			return true;
		}
		return false;
	}

	/**
	 * コピー
	 *
	 * @return bool
	 */
	public function admin_ajax_copy() {

		$this->autoRender = false;
		if(!$this->request->data) {
			$this->ajaxError(500, __d('baser', '無効な処理です。'));
		}
		$user = $this->BcAuth->user();
		$data = $this->BcCrudContent->copy(
					$this->request->data['entityId'],
					$this->request->data['parentId'],
					$this->request->data['title'],
					$user['id'],
					$this->request->data['siteId']
				);
		if ($data) {
			$message = sprintf(
				__d('baser', 'コピーして「%s」を追加しました。'),
				$this->request->data['title']
			);
			$this->BcMessage->setSuccess($message, true, false);
			return json_encode($data['Content']);
		} else {
			$this->ajaxError(500, $this->{$this->modelClass}->validationErrors);
		}
		return false;
	}
}
