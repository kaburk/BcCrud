<?php
$url = urldecode($this->request->params['Content']['url']) . 'detail/' . $this->BcForm->value('BcCrudPost.no');
$fullUrl = $this->BcBaser->getContentsUrl($url, true, $this->request->params['Site']['use_subdomain']);

$this->BcBaser->css(['admin/ckeditor/editor'], ['inline' => true]);
$this->BcBaser->js(['BcCrud.admin/bc_crud_posts/form'], false, [
	'id' => 'AdminBcCrudBcCrudPostsEditScript',
	'data-fullurl' => $fullUrl,
	'data-previewurl' => $this->BcCrud->getPreviewUrl($url, $this->request->params['Site']['use_subdomain'])
]);
?>

<?php
	if ($this->action == 'admin_add'):
		echo $this->BcForm->create(
			'BcCrudPost',
			[
				'type' => 'file',
				'url' => [
					'action' => 'add',
					$bcCrudContent['BcCrudContent']['id'],
				],
				'id' => 'BcCrudPostForm',
			],
		);
	elseif ($this->action == 'admin_edit'):
		echo $this->BcForm->create(
			'BcCrudPost',
			[
				'type' => 'file',
				'url' => [
					'action' => 'edit',
					$bcCrudContent['BcCrudContent']['id'],
					$this->BcForm->value('BcCrudPost.id'),
				],
				'id' => 'BcCrudPostForm',
			],
		);
	endif;

	echo $this->BcForm->hidden('BcCrudPost.id');
	echo $this->BcForm->hidden('BcCrudPost.bc_crud_content_id', ['value' => $bcCrudContent['BcCrudContent']['id']]);
	echo $this->BcForm->hidden('BcCrudPost.mode');
	if (empty($bcCrudContent['BcCrudContent']['use_content'])):
		echo $this->BcForm->hidden('BcCrudPost.content');
	endif;
 ?>

<?php echo $this->BcFormTable->dispatchBefore() ?>

<?php if ($this->action == 'admin_edit'): ?>
	<div class="bca-section bca-section__post-top">
		<span class="bca-post__no">
			<?php echo $this->BcForm->label('BcCrudPost.no', 'No') ?> : <strong><?php echo $this->BcForm->value('BcCrudPost.no') ?></strong>
			<?php echo $this->BcForm->input('BcCrudPost.no', ['type' => 'hidden']) ?>
		</span>

		<span class="bca-post__url">
			<?php
				echo $this->BcBaser->link(
					'<i class="bca-icon--globe"></i>' . $fullUrl,
					$fullUrl,
					[
						'class' => 'bca-text-url',
						'target' => '_blank',
						'data-toggle' => 'tooltip',
						'data-placement' => 'top',
						'title' => '公開URLを開きます',
						'force' => true,
					]
				);
				echo $this->BcForm->button('', [
					'id' => 'BtnCopyUrl',
					'class' => 'bca-btn',
					'type' => 'button',
					'data-bca-btn-type' => 'textcopy',
					'data-bca-btn-category' => 'text',
					'data-bca-btn-size' => 'sm'
				]);
			?>
		</span>
	</div>
<?php endif; ?>


<!-- form -->
<section class="bca-section">
	<table id="FormTable" class="form-table bca-form-table">
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudPost.name', __d('baser', 'タイトル')) ?>
				&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudPost.name',
						[
							'type' => 'text',
							'size' => 80,
							'maxlength' => 255,
							'autofocus' => true,
							'data-input-text-size' => 'full-counter',
							'counter' => true
						]
					);
				?>
				<?php echo $this->BcForm->error('BcCrudPost.name') ?>
			</td>
		</tr>
	<?php if ($bcCrudCategories): ?>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudPost.bc_crud_category_id', __d('baser', 'カテゴリー')) ?>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudPost.bc_crud_category_id',
						[
							'type' => 'select',
							'escape' => true,
							'empty' => __d('baser', '指定なし'),
						]
					);
				?>
				<?php echo $this->BcForm->error('BcCrudPost.bc_crud_category_id') ?>
			</td>
		</tr>
	<?php endif ?>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudPost.eye_catch', __d('baser', 'アイキャッチ画像')) ?>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudPost.eye_catch',
						[
							'type' => 'file',
							'imgsize' => 'thumb',
							'width' => '300'
						]
					);
				?>
				<?php echo $this->BcForm->error('BcCrudPost.eye_catch') ?>
			</td>
		</tr>
	</table>
</section>

<?php if (!empty($bcCrudContent['BcCrudContent']['use_content'])): ?>
<section class="bca-section bca-section__post-content">
	<label for="BcCrudPostContentTmp" class="bca-form-table__label -label">
		<?php echo $this->BcForm->label('BcCrudPost.content', __d('baser', '概要')) ?>
	</label>
	<span class="bca-form-table__input-wrap">
		<?php
			echo $this->BcForm->ckeditor(
				'BcCrudPost.content',
				[
					'editorWidth' => 'auto',
					'editorHeight' => '120px',
					'editorToolType' => 'simple',
					'editorEnterBr' => @$siteConfig['editor_enter_br']
				]
			);
		?>
		<?php echo $this->BcForm->error('BcCrudPost.content') ?>
	</span>
</section>
<?php endif ?>

<section class="bca-section bca-section__post-detail">
	<label for="BcCrudPostDetailTmp" class="bca-form-table__label -label">
		<?php echo $this->BcForm->label('BcCrudPost.content', __d('baser', '本文')) ?>
	</label>
	<span class="bca-form-table__input-wrap">
		<?php
			echo $this->BcForm->editor(
				'BcCrudPost.detail',
				array_merge(
					[
						'type' => 'editor',
						'editor' => @$siteConfig['editor'],
						'editorUseDraft' => true,
						'editorDraftField' => 'detail_draft',
						'editorWidth' => 'auto',
						'editorHeight' => '480px',
						'editorEnterBr' => @$siteConfig['editor_enter_br']
					],
					$editorOptions
				)
			);
		?>
		<?php echo $this->BcForm->error('BcCrudPost.detail') ?>
	</span>
</section>

<section class="bca-section">
	<table class="form-table bca-form-table">
<?php if (!empty($bcCrudContent['BcCrudContent']['use_tag'])): ?>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudTag.BcCrudTag', __d('baser', 'タグ')) ?>
			</th>
			<td class="col-input bca-form-table__input">
				<div id="BcCrudTags" class="bca-form-table__group bca-blogtags">
					<?php
						echo $this->BcForm->input(
							'BcCrudTag.BcCrudTag',
							[
								'type' => 'select',
								'multiple' => 'checkbox',
							]
						);
					?>
					<?php echo $this->BcForm->error('BcCrudTag.BcCrudTag') ?>
				</div>
			</td>
		</tr>
<?php endif ?>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudPost.status', __d('baser', '公開状態')) ?>
				&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudPost.status',
						[
							'type' => 'radio',
						]
					);
				?>
				<?php echo $this->BcForm->error('BcCrudPost.status') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudPost.status', __d('baser', '公開日時')) ?>
			</th>
			<td class="col-input bca-form-table__input">
				<span class="bca-datetimepicker__group">
					<span class="bca-datetimepicker__start">
						<?php
							echo $this->BcForm->input(
								'BcCrudPost.publish_begin',
								[
									'type' => 'dateTimePicker',
									'size' => 12,
									'maxlength' => 10,
									'dateLabel' => ['text' => __d('baser', '開始日付')],
									'timeLabel' => ['text' => __d('baser', '開始時間')]
								],
								true
							);
						?>
					</span>
					<span class="bca-datetimepicker__delimiter">〜</span>
					<span class="bca-datetimepicker__end">
						<?php
							echo $this->BcForm->input(
								'BcCrudPost.publish_end',
								[
									'type' => 'dateTimePicker',
									'size' => 12,
									'maxlength' => 10,
									'dateLabel' => ['text' => __d('baser', '終了日付')],
									'timeLabel' => ['text' => __d('baser', '終了時間')]
								],
								true
							);
						?>
					</span>
				</span>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudPost.status', __d('baser', 'サイト内検索')) ?>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudPost.exclude_search',
						[
							'type' => 'checkbox',
							'label' => __d('baser', 'サイト内検索の検索結果より除外する')
						]
					);
				?>
				<?php echo $this->BcForm->error('BcCrudPost.publish_begin') ?>
				<?php echo $this->BcForm->error('BcCrudPost.publish_end') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudPost.user_id', __d('baser', '作成者')) ?>
				&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
			<?php if (isset($user) && $user['user_group_id'] == Configure::read('BcApp.adminGroupId')): ?>
				<?php
					echo $this->BcForm->input(
						'BcCrudPost.user_id',
						[
							'type' => 'select',
						]
					);
				?>
				<?php echo $this->BcForm->error('BcCrudPost.user_id') ?>
			<?php else: ?>
			<?php if (isset($users[$this->BcForm->value('BcCrudPost.user_id')])): ?>
				<?php echo h($users[$this->BcForm->value('BcCrudPost.user_id')]) ?>
			<?php endif ?>
				<?php echo $this->BcForm->hidden('BcCrudPost.user_id') ?>
			<?php endif ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudPost.posts_date', __d('baser', '投稿日時')) ?>
				&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudPost.posts_date',
						[
							'type' => 'dateTimePicker',
							'size' => 12,
							'maxlength' => 10
						],
						true
					);
				?>
				<?php echo $this->BcForm->error('BcCrudPost.posts_date') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudPost.sort', __d('baser', '並び順')) ?>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudPost.sort',
						[
							'type' => 'text',
							'size' => 10,
							'maxlength' => 10,
							'autofocus' => true,
						]
					);
				?>
				<?php echo $this->BcForm->error('BcCrudPost.sort') ?>
			</td>
		</tr>
		<?php echo $this->BcForm->dispatchAfterForm() ?>
	</table>
</section>

<?php echo $this->BcFormTable->dispatchAfter() ?>

<!-- button -->
<section class="submit bca-actions">
<?php if ($this->action == 'admin_edit' || $this->action == 'admin_add'): ?>
	<div class="bca-actions__main">
		<?php
			$this->BcBaser->link(
				__d('baser', '一覧に戻る'),
				[
					'action' => 'index',
					$bcCrudContent['BcCrudContent']['id'],
				],
				[
					'class' => 'button bca-btn bca-actions__item',
				]
			);
		?>
		<?php
			echo $this->BcForm->button(
				__d('baser', 'プレビュー'),
				[
					'id' => 'BtnPreview',
					'div' => false,
					'class' => 'button bca-btn bca-actions__item',
					'data-bca-btn-type' => 'preview',
				]
			);
		?>
		<?php
			echo $this->BcForm->button(
				__d('baser', '保存'),
				[
					'type' => 'submit',
					'id' => 'BtnSave',
					'div' => false,
					'class' => 'button bca-btn bca-actions__item',
					'data-bca-btn-type' => 'save',
					'data-bca-btn-size' => 'lg',
					'data-bca-btn-width' => 'lg',
				]
			);
		?>
	</div>
<?php endif ?>
<?php if ($this->action == 'admin_edit'): ?>
	<div class="bca-actions__sub">
		<?php
			$this->BcBaser->link(
				__d('baser', '削除'),
				[
					'action' => 'delete',
					$bcCrudContent['BcCrudContent']['id'],
					$this->BcForm->value('BcCrudPost.id'),
				],
				[
					'class' => 'submit-token button bca-btn bca-actions__item',
					'data-bca-btn-type' => 'delete',
					'data-bca-btn-size' => 'sm',
					'data-bca-btn-color' => 'danger'
				],
				sprintf(
					__d('baser', '%s を本当に削除してもいいですか？\n※ ゴミ箱に入らず完全に消去されます。'),
					$this->BcForm->value('BcCrudPost.name')
				),
				false
			);
		?>
	</div>
<?php endif ?>
</section>

<?php echo $this->BcForm->end() ?>
