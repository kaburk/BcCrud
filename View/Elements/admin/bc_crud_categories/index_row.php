<?php
?>
<tr<?php $this->BcListTable->rowClass($this->BcCrud->allowPublish('BcCrud.BcCrudCategory', $data), $data) ?>>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--select">
		<?php // 選択 ?>
		<?php if ($this->BcBaser->isAdminUser()): ?>
			<?php
				echo $this->BcForm->input(
					'ListTool.batch_targets.' . $data['BcCrudCategory']['id'],
					[
						'type' => 'checkbox',
						'label'=> '<span class="bca-visually-hidden">' . __d('baser', 'チェックする') . '</span>',
						'class' => 'batch-targets bca-checkbox__input',
						'value' => $data['BcCrudCategory']['id'],
					]
				);
			?>
		<?php endif ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--no">
		<?php // No ?>
		<?php echo $data['BcCrudCategory']['no']; ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--sort">
		<?php // 並び順 ?>
		<?php echo $data['BcCrudCategory']['sort']; ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--eye_catch">
		<?php // アイキャッチ ?>
		<div class="eye_catch-wrap">
			<div class="eye_catch">
				<?php
					echo $this->BcUpload->uploadImage(
						'BcCrudCategory.eye_catch',
						$data['BcCrudCategory']['eye_catch'],
						[
							'noimage' => '/bc_crud/img/noimage.png',
							'imgsize' => 'thumb',
						]
					);
				?>
			</div>
		</div>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--name">
		<?php // カテゴリ名 ?>
		<?php
			echo $this->BcBaser->link(
				$data['BcCrudCategory']['name'],
				[
					'action' => 'edit',
					$data['BcCrudCategory']['bc_crud_content_id'],
					$data['BcCrudCategory']['id']
				]
			);
		?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--title">
		<?php // カテゴリタイトル ?>
		<?php echo $data['BcCrudCategory']['title']; ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--date">
		<?php // 作者 ?>
		<?php if (!empty($users[$data['BcCrudCategory']['user_id']])): ?>
			<?php echo h($users[$data['BcCrudCategory']['user_id']]) ?>
		<?php endif; ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--date">
		<?php echo $this->BcTime->format('Y-m-d', $data['BcCrudCategory']['created']) ?><br />
		<?php echo $this->BcTime->format('Y-m-d', $data['BcCrudCategory']['modified']) ?>
	</td>

	<?php echo $this->BcListTable->dispatchShowRow($data) ?>

	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--actions">
		<?php
			$this->BcBaser->link(
				'',
				[
					'action' => 'ajax_unpublish',
					$data['BcCrudCategory']['bc_crud_content_id'],
					$data['BcCrudCategory']['id'],
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
					$data['BcCrudCategory']['bc_crud_content_id'],
					$data['BcCrudCategory']['id'],
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
				$this->request->params['Content']['url'] . 'category/' . $data['BcCrudCategory']['name'],
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
					$data['BcCrudCategory']['bc_crud_content_id'],
					$data['BcCrudCategory']['id'],
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
					$data['BcCrudCategory']['bc_crud_content_id'],
					$data['BcCrudCategory']['id'],
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
					$data['BcCrudCategory']['bc_crud_content_id'],
					$data['BcCrudCategory']['id'],
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
