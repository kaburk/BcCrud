<?php
$this->BcBaser->css(['BcCrud.style'], false);
if ($this->request->params['action'] === 'index'):
	// 一覧の時
	$description = $this->request->params['Content']['description'];
	$metaDescription = $description;
else:
	// カテゴリ、タグ一覧の時
	$description = '';
	if ($this->request->params['action'] === 'category') $description = $category['BcCrudCategory']['description'];
	if ($this->request->params['action'] === 'tags'    ) $description = $tag['BcCrudTag']['description'];
	if ($description) {
		$metaDescription = $this->request->params['Content']['title'] . '｜' . $description;
	} else {
		$metaDescription = $this->request->params['Content']['title'] . '｜' . $this->BcBaser->getContentsTitle() . __('の一覧です。');
	}
endif;
if ($metaDescription) {
	// メタタグへのセット
	$this->BcBaser->setDescription($this->BcText->truncate(h(preg_replace('[\n|\r|\r\n|\t]', '', strip_tags($metaDescription))), 50));
}
?>

<h2 class="bs-blog-title">
	<?php
		// コンテンツ名
		echo $this->request->params['Content']['title'];

		// カテゴリ名・タグ名
		if ($this->request->params['action'] !== 'index'):
			echo ' - ' . $this->BcBaser->getContentsTitle();
		endif;
	?>
</h2>

<?php // コンテンツ：アイキャッチ ?>
<?php if ($this->request->params['Content']['eyecatch']): ?>
<div class="bs-blog-eye-catch">
	<?php
		if ($this->request->params['action'] === 'index' && $this->request->params['Content']['eyecatch']):
			// 一覧の時
			echo $this->BcUpload->uploadImage(
				'Content.eyecatch',
				$this->request->params['Content']['eyecatch'],
				[
					'imgsize' => 'normal',
					'noimage' => '/bc_crud/img/noimage.png',
					'link' => false,
				]
			);
		elseif ($this->request->params['action'] === 'category' && $category['BcCrudCategory']['eye_catch']):
			// カテゴリの時
			echo $this->BcUpload->uploadImage(
				'BcCrudCategory.eye_catch',
				$category['BcCrudCategory']['eye_catch'],
				[
					'imgsize' => 'normal',
					'noimage' => '/bc_crud/img/noimage.png',
					'link' => false,
				]
			);
		elseif ($this->request->params['action'] === 'tags' && $tag['BcCrudTag']['eye_catch']):
			// タグの時
			echo $this->BcUpload->uploadImage(
				'BcCrudTag.eye_catch',
				$tag['BcCrudTag']['eye_catch'],
				[
					'imgsize' => 'normal',
					'noimage' => '/bc_crud/img/noimage.png',
					'link' => false,
				]
			);
		endif;
	?>
</div>
<?php endif ?>

<?php // コンテンツ：説明文 ?>
<?php if ($description): ?>
<div class="bs-blog-description">
	<?php echo nl2br($description) ?>
</div>
<?php endif ?>

<?php // 記事 ?>
<section class="bs-blog-post">
<?php if (!empty($posts)): ?>
	<?php foreach ($posts as $post): ?>
	<article class="bs-blog-post__item clearfix">
		<?php
			// 概要欄を使わない時の処理
			if (!$bcCrudContent['BcCrudContent']['use_content']) {
				$post['BcCrudPost']['content'] = '';
			}

			$url = $this->request->params['Content']['url'] . 'detail/' . $post['BcCrudPost']['no'];
			$image = $this->BcUpload->uploadImage(
				'BcCrudPost.eye_catch',
				$post['BcCrudPost']['eye_catch'],
				[
					'imgsize' => 'thumb',
					'noimage' => '/bc_crud/img/noimage.png',
					'width' => 150,
					'link' => false,
				]
			);
			echo $this->BcBaser->getLink($image, $url, ['class' => 'bs-blog-post__item-eye-catch']);
		?>
		<span class="bs-blog-post__item-date"><?php echo date('Y.m.d', strtotime($post['BcCrudPost']['posts_date'])) ?></span>
		<?php
			if (isset($post['BcCrudCategory']['name'])):
				$categoryUrl = $this->request->params['Content']['url'] . 'category/' . $post['BcCrudCategory']['name'];
				echo $this->BcBaser->getLink($post['BcCrudCategory']['title'], $categoryUrl, ['class' => 'bs-blog-post__item-category']);
			endif;
		?>
		<?php
			if (isset($post['BcCrudTag'])):
				foreach($post['BcCrudTag'] as $tag):
					$tagUrl = $this->request->params['Content']['url'] . 'tags/' . $tag['name'];
					echo $this->BcBaser->getLink($tag['title'], $tagUrl, ['class' => 'bs-blog-post__item-category']);
				endforeach;
			endif;
		?>
		<span class="bs-blog-post__item-title">
			<?php echo $this->BcBaser->getLink($post['BcCrudPost']['name'], $url); ?>
		</span>
		<?php
			$detail = strip_tags($post['BcCrudPost']['content'] . $post['BcCrudPost']['detail']);
			if ($detail): ?>
		<div class="bs-top-post__item-detail">
			<?php echo $this->BcText->truncate($detail, 46); ?>
		</div>
		<?php endif ?>
	</article>
	<?php endforeach; ?>
<?php else: ?>
	<p class="bs-blog-no-data"><?php echo __('記事がありません。'); ?></p>
<?php endif ?>
</section>

<?php $this->BcBaser->pagination('simple'); ?>
