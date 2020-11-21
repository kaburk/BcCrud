<?php
$this->BcBaser->css(['BcCrud.style'], false);
// 概要欄を使わない時の処理
if (!$bcCrudContent['BcCrudContent']['use_content']) {
	$post['BcCrudPost']['content'] = '';
}

$description = 	$this->BcText->truncate(strip_tags($post['BcCrudPost']['content'] . $post['BcCrudPost']['detail']), 50);
// メタタグへのセット
$this->BcBaser->setDescription($this->BcText->truncate(h(preg_replace('[\n|\r|\r\n|\t]', '', strip_tags($description))), 50));
?>

<h2 class="bs-blog-title"><?php echo $this->request->params['Content']['title'] ?></h2>

<h3 class="bs-blog-post-title"><?php echo $post['BcCrudPost']['name'] ?></h3>

<article class="bs-single-post">
	<div class="bs-single-post__meta">
		<?php
			if (isset($post['BcCrudCategory']['name'])):
				$categoryUrl = $this->request->params['Content']['url'] . 'category/' . $post['BcCrudCategory']['name'];
				echo $this->BcBaser->getLink($post['BcCrudCategory']['title'], $categoryUrl, ['class' => 'bs-single-post__meta-category']);
			endif;
		?>
		<?php
			if (isset($post['BcCrudTag'])):
				foreach($post['BcCrudTag'] as $tag):
					$tagUrl = $this->request->params['Content']['url'] . 'tags/' . $tag['name'];
					echo $this->BcBaser->getLink($tag['title'], $tagUrl, ['class' => 'bs-single-post__meta-category']);
				endforeach;
			endif;
		?>
		<span class="bs-single-post__meta-date">
			<?php echo date('Y.m.d', strtotime($post['BcCrudPost']['posts_date'])) ?>
		</span>
	</div>
<?php if ($post['BcCrudPost']['eye_catch']): ?>
	<div class="bs-single-post__eye-catch">
		<?php
			echo $this->BcUpload->uploadImage(
				'BcCrudPost.eye_catch',
				$post['BcCrudPost']['eye_catch'],
				[
					'imgsize' => 'thumb',
					'noimage' => '/bc_crud/img/noimage.png',
					'link' => false,
				]
			);
		?>
	</div>
<?php endif ?>
	<?php echo $post['BcCrudPost']['detail'] ?>

</article>

<div class="bs-blog-post-return">
	<?php echo $this->BcBaser->getLink('一覧に戻る', $this->request->params['Content']['url'], ['class' => 'bs-button-small']); ?>
</div>

