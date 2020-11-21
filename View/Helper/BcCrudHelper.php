<?php
class BcCrudHelper extends AppHelper {

	/**
	 * ヘルパー
	 *
	 * @var array
	 */
	public $helpers = [
		'BcHtml',
		'BcTime',
		'BcBaser',
		'BcUpload',
		'BcContents',
	];

	/**
	 * コンストラクタ
	 *
	 * @param View $View Viewオブジェクト
	 * @param array $settings 設定
	 * @return void
	 */
	public function __construct(View $View, $settings = []) {
		parent::__construct($View, $settings);
	}

	/**
	 * 公開状態を取得する
	 *
	 * @param string $modelName モデル名
	 * @param array $data データ
	 * @return boolean 公開状態
	 */
	public function allowPublish($modelName, $data) {
		if (ClassRegistry::isKeySet($modelName)) {
			$model = ClassRegistry::getObject($modelName);
		} else {
			$model = ClassRegistry::init($modelName);
		}
		if (method_exists($model, 'allowPublish')) {
			return $model->allowPublish($data);
		} else {
			return false;
		}
	}

	/**
	 * プレビュー用のURLを取得する
	 *
	 * @param string $url 元となるURL
	 * @param bool $useSubDomain サブドメインを利用してるかどうか
	 * @return string
	 */
	public function getPreviewUrl($url, $useSubDomain = false) {
		if($useSubDomain) {
			$targetSite = BcSite::findByUrl($url);
			return $this->BcBaser->getUrl($targetSite->getPureUrl($url)) . '?host=' . $targetSite->host;
		} else {
			return $this->BcBaser->getContentsUrl($url, false, false, true);
		}
	}


	/**
	 * コンテンツテンプレートを取得
	 *
	 * コンボボックスのソースとして利用
	 *
	 * @return array テンプレート一覧
	 */
	public function getTemplates($siteId = 0) {

		$site = BcSite::findById($siteId);
		$theme = $this->BcBaser->siteConfig['theme'];
		if($site->theme) {
			$theme = $site->theme;
		}
		$templatesPathes = array_merge(App::path('View', 'BcCrud'), App::path('View'));
		if ($theme) {
			array_unshift($templatesPathes, WWW_ROOT . 'theme' . DS . $theme . DS);
		}

		$_templates = [];
		foreach ($templatesPathes as $templatePath) {
			$templatePath .= 'BcCrud' . DS;
			$folder = new Folder($templatePath);
			$files = $folder->read(true, true);
			$foler = null;
			if ($files[0]) {
				if ($_templates) {
					$_templates = am($_templates, $files[0]);
				} else {
					$_templates = $files[0];
				}
			}
		}

		$excludes = Configure::read('BcAgent');
		$excludes = array_keys($excludes);

		$excludes[] = 'rss';
		$templates = [];
		foreach ($_templates as $template) {
			if (!in_array($template, $excludes)) {
				$templates[$template] = $template;
			}
		}

		return $templates;
	}
}
