<?php
$url = urldecode($this->request->params['Content']['url']) . 'tags/' . $this->BcForm->value('BcCrudTag.name');
$fullUrl = $this->BcBaser->getContentsUrl($url, true, $this->request->params['Site']['use_subdomain']);
?>

<?php
	if ($this->action == 'admin_add'):
		echo $this->BcForm->create(
			'BcCrudTag',
			[
				'type' => 'file',
				'url' => [
					'action' => 'add',
					$bcCrudContent['BcCrudContent']['id'],
				],
				'id' => 'BcCrudTagForm',
			],
		);
	elseif ($this->action == 'admin_edit'):
		echo $this->BcForm->create(
			'BcCrudTag',
			[
				'type' => 'file',
				'url' => [
					'action' => 'edit',
					$bcCrudContent['BcCrudContent']['id'],
					$this->BcForm->value('BcCrudTag.id'),
				],
				'id' => 'BcCrudTagForm',
			],
		);
	endif;

	echo $this->BcForm->hidden('BcCrudTag.id');
	echo $this->BcForm->hidden('BcCrudTag.bc_crud_content_id', ['value' => $bcCrudContent['BcCrudContent']['id']]);
	echo $this->BcForm->hidden('BcCrudTag.mode');
 ?>

<?php echo $this->BcFormTable->dispatchBefore() ?>

<?php if ($this->action == 'admin_edit'): ?>
	<div class="bca-section bca-section__post-top">
		<span class="bca-post__no">
			<?php echo $this->BcForm->label('BcCrudTag.no', 'No') ?> : <strong><?php echo $this->BcForm->value('BcCrudTag.no') ?></strong>
			<?php echo $this->BcForm->input('BcCrudTag.no', ['type' => 'hidden']) ?>
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
				<?php echo $this->BcForm->label('BcCrudTag.name', __d('baser', 'タグ名')) ?>
				&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudTag.name',
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
				<?php echo $this->BcForm->error('BcCrudTag.name') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudTag.title', __d('baser', 'タグタイイトル')) ?>
				&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudTag.title',
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
				<?php echo $this->BcForm->error('BcCrudTag.title') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudTag.description', __d('baser', '説明文')) ?>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudTag.description',
						[
							'type' => 'textarea',
							'rows' => 5,
							'maxlength' => 9999,
							'autofocus' => true,
							'data-input-text-size' => 'full-counter',
							'counter' => true
						]
					);
				?>
				<?php echo $this->BcForm->error('BcCrudTag.description') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudTag.eye_catch', __d('baser', 'アイキャッチ画像')) ?>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudTag.eye_catch',
						[
							'type' => 'file',
							'imgsize' => 'thumb',
							'width' => '300'
						]
					);
				?>
				<?php echo $this->BcForm->error('BcCrudTag.eye_catch') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudTag.status', __d('baser', '公開状態')) ?>
				&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudTag.status',
						[
							'type' => 'radio',
						]
					);
				?>
				<?php echo $this->BcForm->error('BcCrudTag.status') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudTag.user_id', __d('baser', '作成者')) ?>
				&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
			<?php if (isset($user) && $user['user_group_id'] == Configure::read('BcApp.adminGroupId')): ?>
				<?php
					echo $this->BcForm->input(
						'BcCrudTag.user_id',
						[
							'type' => 'select',
						]
					);
				?>
				<?php echo $this->BcForm->error('BcCrudTag.user_id') ?>
			<?php else: ?>
			<?php if (isset($users[$this->BcForm->value('BcCrudTag.user_id')])): ?>
				<?php echo h($users[$this->BcForm->value('BcCrudTag.user_id')]) ?>
			<?php endif ?>
				<?php echo $this->BcForm->hidden('BcCrudTag.user_id') ?>
			<?php endif ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('BcCrudTag.sort', __d('baser', '並び順')) ?>
			</th>
			<td class="col-input bca-form-table__input">
				<?php
					echo $this->BcForm->input(
						'BcCrudTag.sort',
						[
							'type' => 'text',
							'size' => 10,
							'maxlength' => 10,
							'autofocus' => true,
						]
					);
				?>
				<?php echo $this->BcForm->error('BcCrudTag.sort') ?>
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
					$this->BcForm->value('BcCrudTag.id'),
				],
				[
					'class' => 'submit-token button bca-btn bca-actions__item',
					'data-bca-btn-type' => 'delete',
					'data-bca-btn-size' => 'sm',
					'data-bca-btn-color' => 'danger'
				],
				sprintf(
					__d('baser', '%s を本当に削除してもいいですか？\n※ ゴミ箱に入らず完全に消去されます。'),
					$this->BcForm->value('BcCrudTag.title')
				),
				false
			);
		?>
	</div>
<?php endif ?>
</section>

<?php echo $this->BcForm->end() ?>
