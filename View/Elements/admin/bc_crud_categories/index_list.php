<?php
$this->BcListTable->setColumnNumber(9);
?>

<div class="bca-data-list__top">
	<!-- 一括処理 -->
	<?php if ($this->BcBaser->isAdminUser()): ?>
		<div>
			<?php
				echo $this->BcForm->input(
					'ListTool.batch',
					[
						'type' => 'select',
						'options' => [
							'publish' => __d('baser', '有効'),
							'unpublish' => __d('baser', '無効'),
							'del' => __d('baser', '削除'),
						],
						'empty' => __d('baser', '一括処理'),
						'data-bca-select-size' =>'lg',
					]
				);
				echo $this->BcForm->button(
					__d('baser', '適用'),
					[
						'id' => 'BtnApplyBatch',
						'disabled' => 'disabled',
						'class' => 'bca-btn',
						'data-bca-btn-size' => 'lg',
					]
				);
			?>
		</div>
	<?php endif ?>
	<div class="bca-data-list__sub">
		<!-- pagination -->
		<?php $this->BcBaser->element('pagination') ?>
	</div>
</div>

<!-- list -->
<table class="list-table bca-table-listup" id="ListTable">
	<thead class="bca-table-listup__thead">
		<tr>
			<th class="list-tool bca-table-listup__thead-th  bca-table-listup__thead-th--select">
				<?php echo $this->BcForm->input('ListTool.checkall', ['type' => 'checkbox', 'label' => '&nbsp;']) ?>
			</th>
			<th class="bca-table-listup__thead-th">
				<?php // No ?>
				<?php
					echo $this->Paginator->sort('no',
						[
							'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', 'No'),
							'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', 'No')
						],
						[
							'escape' => false,
							'class' => 'btn-direction bca-table-listup__a'
						]
						);
				?>
			</th>
			<th class="bca-table-listup__thead-th">
				<?php // 並び順 ?>
				<?php
					echo $this->Paginator->sort('sort',
						[
							'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', '並び順'),
							'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', '並び順')
						],
						[
							'escape' => false,
							'class' => 'btn-direction bca-table-listup__a'
						]
						);
				?>
			</th>
			<th class="bca-table-listup__thead-th">
				<?php // アイキャッチ ?>
			</th>
			<th class="bca-table-listup__thead-th">
				<?php
					echo $this->Paginator->sort(
						'name',
						[
							'asc' => '<i class="bca-icon--asc"></i>'. __d('baser', 'カテゴリ名'),
							'desc' => '<i class="bca-icon--desc"></i>'. __d('baser', 'カテゴリ名'),
						],
						[
							'escape' => false,
							'class' => 'btn-direction bca-table-listup__a',
						]
					);
				?>
			</th>
			<th class="bca-table-listup__thead-th">
				<?php // タイトル ?>
				<?php
					echo $this->Paginator->sort(
						'title',
						[
							'asc' => '<i class="bca-icon--asc"></i>'. __d('baser', 'カテゴリタイトル'),
							'desc' => '<i class="bca-icon--desc"></i>'. __d('baser', 'カテゴリタイトル'),
						],
						[
							'escape' => false,
							'class' => 'btn-direction bca-table-listup__a',
						]
					);
				?>
			</th>

			<?php echo $this->BcListTable->dispatchShowHead() ?>

			<th class="bca-table-listup__thead-th">
				<?php
					echo $this->Paginator->sort(
						'user_id',
						[
							'asc' => '<i class="bca-icon--asc"></i>'. __d('baser', '作成者'),
							'desc' => '<i class="bca-icon--desc"></i>'. __d('baser', '作成者'),
						],
						[
							'escape' => false,
							'class' => 'btn-direction bca-table-listup__a',
						]
					);
				?>
			</th>
			<th class="bca-table-listup__thead-th">
				<?php
					echo $this->Paginator->sort(
						'created',
						[
							'asc' => '<i class="bca-icon--asc"></i>'. __d('baser', '登録日'),
							'desc' => '<i class="bca-icon--desc"></i>'. __d('baser', '登録日'),
						],
						[
							'escape' => false,
							'class' => 'btn-direction bca-table-listup__a',
						]
					);
				?><br>
				<?php
					echo $this->Paginator->sort(
						'modified',
						[
							'asc' => '<i class="bca-icon--asc"></i>'. __d('baser', '更新日'),
							'desc' => '<i class="bca-icon--desc"></i>'. __d('baser', '更新日'),
						],
						[
							'escape' => false,
							'class' => 'btn-direction bca-table-listup__a',
						]
					);
				?>
			</th>
			<th class="bca-table-listup__thead-th">
				<?php // アクション ?>
				<?php echo __d('baser', 'アクション') ?>
			</th>
		</tr>
	</thead>
	<tbody class="bca-table-listup__tbody">
<?php
	if (!empty($posts)):
		foreach ($posts as $data):
			$this->BcBaser->element('bc_crud_categories/index_row', ['data' => $data]);
		endforeach;
	else:
?>
		<tr>
			<td colspan="<?php echo $this->BcListTable->getColumnNumber() ?>" class="bca-table-listup__tbody-td">
				<p class="no-data">
					<?php echo __d('baser', 'データが見つかりませんでした。') ?>
				</p>
			</td>
		</tr>
<?php endif; ?>
	</tbody>
</table>

<div class="bca-data-list__bottom">
	<div class="bca-data-list__sub">
		<!-- pagination -->
		<?php $this->BcBaser->element('pagination') ?>
		<!-- list-num -->
		<?php $this->BcBaser->element('list_num') ?>
	</div>
</div>
