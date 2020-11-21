<?php
?>
<tr<?php $this->BcListTable->rowClass($this->BcCrud->allowPublish('BcCrud.BcCrudTag', $data), $data) ?>>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--select">
		<?php // 選択 ?>
		<?php if ($this->BcBaser->isAdminUser()): ?>
			<?php
				echo $this->BcForm->input(
					'ListTool.batch_targets.' . $data['BcCrudTag']['id'],
					[
						'type' => 'checkbox',
						'label'=> '<span class="bca-visually-hidden">' . __d('baser', 'チェックする') . '</span>',
						'class' => 'batch-targets bca-checkbox__input',
						'value' => $data['BcCrudTag']['id'],
					]
				);
			?>
		<?php endif ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--no">
		<?php // No ?>
		<?php echo $data['BcCrudTag']['no']; ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--sort">
		<?php // 並び順 ?>
		<?php echo $data['BcCrudTag']['sort']; ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--eye_catch">
		<?php // アイキャッチ ?>
		<div class="eye_catch-wrap">
			<div class="eye_catch">
				<?php
					echo $this->BcUpload->uploadImage(
						'BcCrudTag.eye_catch',
						$data['BcCrudTag']['eye_catch'],
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
		<?php // タグ名 ?>
		<?php
			echo $this->BcBaser->link(
				$data['BcCrudTag']['name'],
				[
					'action' => 'edit',
					$data['BcCrudTag']['bc_crud_content_id'],
					$data['BcCrudTag']['id']
				]
			);
		?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--title">
		<?php // タグタイトル ?>
		<?php echo $data['BcCrudTag']['title']; ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--date">
		<?php // 作者 ?>
		<?php if (!empty($users[$data['BcCrudTag']['user_id']])): ?>
			<?php echo h($users[$data['BcCrudTag']['user_id']]) ?>
		<?php endif; ?>
	</td>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--date">
		<?php echo $this->BcTime->format('Y-m-d', $data['BcCrudTag']['created']) ?><br />
		<?php echo $this->BcTime->format('Y-m-d', $data['BcCrudTag']['modified']) ?>
	</td>

	<?php echo $this->BcListTable->dispatchShowRow($data) ?>

	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--actions">
		<?php
			$this->BcBaser->link(
				'',
				[
					'action' => 'ajax_unpublish',
					$data['BcCrudTag']['bc_crud_content_id'],
					$data['BcCrudTag']['id'],
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
					$data['BcCrudTag']['bc_crud_content_id'],
					$data['BcCrudTag']['id'],
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
				$this->request->params['Content']['url'] . 'tags/' . $data['BcCrudTag']['name'],
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
					$data['BcCrudTag']['bc_crud_content_id'],
					$data['BcCrudTag']['id'],
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
					$data['BcCrudTag']['bc_crud_content_id'],
					$data['BcCrudTag']['id'],
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
					$data['BcCrudTag']['bc_crud_content_id'],
					$data['BcCrudTag']['id'],
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
