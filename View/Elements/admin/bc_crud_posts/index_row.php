<?php
?>
<tr<?php $this->BcListTable->rowClass($this->BcCrud->allowPublish('BcCrud.BcCrudPost', $data), $data) ?>>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--select">
		<?php // 選択 ?>
		<?php if ($this->BcBaser->isAdminUser()): ?>
			<?php
				echo $this->BcForm->input(
					'ListTool.batch_targets.' . $data['BcCrudPost']['id'],
					[
						'type' => 'checkbox',
						'label'=> '<span class="bca-visually-hidden">' . __d('baser', 'チェックする') . '</span>',
						'class' => 'batch-targets bca-checkbox__input',
						'value' => $data['BcCrudPost']['id'],
					]
				);
			?>
		<?php endif ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--no">
		<?php // No ?>
		<?php echo $data['BcCrudPost']['no']; ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--sort">
		<?php // 並び順 ?>
		<?php echo $data['BcCrudPost']['sort']; ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--eye_catch">
		<?php // アイキャッチ ?>
		<div class="eye_catch-wrap">
			<div class="eye_catch">
				<?php
					echo $this->BcUpload->uploadImage(
						'BcCrudPost.eye_catch',
						$data['BcCrudPost']['eye_catch'],
						[
							'noimage' => '/bc_crud/img/noimage.png',
							'imgsize' => 'thumb',
						]
					);
				?>
			</div>
		</div>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--title">
		<?php // タイトル ?>
		<?php
			echo $this->BcBaser->link(
				$data['BcCrudPost']['name'],
				[
					'action' => 'edit',
					$data['BcCrudPost']['bc_crud_content_id'],
					$data['BcCrudPost']['id']
				]
			);
		?>
	</td>
<?php if ($bcCrudContent['BcCrudContent']['use_category']): ?>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--category">
		<?php // カテゴリ ?>
		<?php if (!empty($data['BcCrudCategory']['title'])): ?>
			<?php echo h($data['BcCrudCategory']['title']) ?>
		<?php endif; ?>
	</td>
<?php endif; ?>
<?php if ($bcCrudContent['BcCrudContent']['use_tag']): ?>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--tag">
		<?php // タグ ?>
		<?php
			if (!empty($data['BcCrudTag'])):
				$tags = Hash::extract($data['BcCrudTag'], '{n}.title');
		?>
		<span class="tag"><?php echo implode('</span><span class="tag">', h($tags)) ?></span>
		<?php endif ?>
	</td>
<?php endif ?>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--date">
		<?php // 投稿日 ?>
		<?php echo $this->BcTime->format('Y-m-d', $data['BcCrudPost']['posts_date']); ?>
		<br>
		<?php // 作者 ?>
		<?php if (!empty($users[$data['BcCrudPost']['user_id']])): ?>
			<?php echo h($users[$data['BcCrudPost']['user_id']]) ?>
		<?php endif; ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--date">
		<?php echo $this->BcTime->format('Y-m-d', $data['BcCrudPost']['created']) ?><br />
		<?php echo $this->BcTime->format('Y-m-d', $data['BcCrudPost']['modified']) ?>
	</td>

	<?php echo $this->BcListTable->dispatchShowRow($data) ?>

	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--actions">
		<?php
			$this->BcBaser->link(
				'',
				[
					'action' => 'ajax_unpublish',
					$data['BcCrudPost']['bc_crud_content_id'],
					$data['BcCrudPost']['id'],
				],
				[
					'title' => __d('baser', '非公開'),
					'class' => 'btn-unpublish bca-btn-icon',
					'data-bca-btn-type' => 'unpublish',
					'data-bca-btn-size' => 'lg',
				]
			);
			$this->BcBaser->link(
				'',
				[
					'action' => 'ajax_publish',
					$data['BcCrudPost']['bc_crud_content_id'],
					$data['BcCrudPost']['id'],
				],
				[
					'title' => __d('baser', '公開'),
					'class' => 'btn-publish bca-btn-icon',
					'data-bca-btn-type' => 'publish',
					'data-bca-btn-size' => 'lg',
				]
			);
			$this->BcBaser->link(
				'',
				$this->request->params['Content']['url'] . 'detail/' . $data['BcCrudPost']['no'],
				[
					'title' => __d('baser', '確認'),
					'target' => '_blank',
					'class' => 'bca-btn-icon',
					'data-bca-btn-type' => 'preview',
					'data-bca-btn-size' => 'lg',
				]
			);
			$this->BcBaser->link(
				'',
				[
					'action' => 'edit',
					$data['BcCrudPost']['bc_crud_content_id'],
					$data['BcCrudPost']['id'],
				],
				[
					'title' => __d('baser', '編集'),
					'class' => ' bca-btn-icon',
					'data-bca-btn-type' => 'edit',
					'data-bca-btn-size' => 'lg',
				]
			);
			$this->BcBaser->link(
				'',
				[
					'action' => 'ajax_copy',
					$data['BcCrudPost']['bc_crud_content_id'],
					$data['BcCrudPost']['id'],
				],
				[
					'title' => __d('baser', 'コピー'),
					'class' => 'btn-copy bca-icon--copy bca-btn-icon',
					'data-bca-btn-type' => 'copy',
					'data-bca-btn-size' => 'lg',
				]
			);
			$this->BcBaser->link(
				'',
				[
					'action' => 'ajax_delete',
					$data['BcCrudPost']['bc_crud_content_id'],
					$data['BcCrudPost']['id'],
				],
				[
					'title' => __d('baser', '削除'),
					'class' => 'btn-delete bca-btn-icon',
					'data-bca-btn-type' => 'delete',
					'data-bca-btn-size' => 'lg',
				]
			);
		?>
	</td>
</tr>
