<?php echo $this->BcForm->create('BcCrudPost', ['url' => ['action' => 'index', $bcCrudContent['BcCrudContent']['id']]]) ?>
<p class="bca-search__input-list">
	<span class="bca-search__input-item">
		<?php
			echo $this->BcForm->label(
				'name',
				__d('baser', 'タイトル'),
				[
					'class' => 'bca-search__input-item-label',
				]
			);
			echo $this->BcForm->input(
				'name',
				[
					'type' => 'text',
					'size' => '30',
					'class' => 'bca-textbox__input',
				]
			);
		?>
	</span>
	<span class="bca-search__input-item">
		<?php
			echo $this->BcForm->label(
				'bc_crud_category_id',
				__d('baser', 'カテゴリー'),
				[
					'class' => 'bca-search__input-item-label',
				]
			);
			echo $this->BcForm->input(
				'bc_crud_category_id',
				[
					'type' => 'select',
					'escape' => true,
					'empty' => __d('baser', '指定なし'),
				]
			);
		?>
	</span>
<?php if ($bcCrudContent['BcCrudContent']['use_tag'] && isset($bcCrudTags)): ?>
	<span class="bca-search__input-item">
		<?php
			echo $this->BcForm->label(
				'blog_tag_id',
				__d('baser', 'タグ'),
				[
					'class' => 'bca-search__input-item-label',
				]
			);
			echo $this->BcForm->input(
				'blog_tag_id',
				[
					'type' => 'select',
					'options' => $bcCrudTags,
					'escape' => true,
					'empty' => __d('baser', '指定なし'),
				]
			);
		?>
	</span>
<?php endif ?>
	<span class="bca-search__input-item">
		<?php
			echo $this->BcForm->label(
				'status',
				__d('baser', '公開設定'),
				[
					'class' => 'bca-search__input-item-label',
				]
			);
			echo $this->BcForm->input(
				'status',
				[
					'type' => 'select',
					'options' => $this->BcText->booleanMarkList(),
					'empty' => __d('baser', '指定なし'),
				]
			);
		?>
	</span>
	<span class="bca-search__input-item">
		<?php
			echo $this->BcForm->label(
				'user_id',
				__d('baser', '作成者'),
				[
					'class' => 'bca-search__input-item-label',
				]
			);
			echo $this->BcForm->input(
				'user_id',
				[
					'type' => 'select',
					'options' => $users,
					'empty' => __d('baser', '指定なし'),
				]
			);
		?>
	</span>

	<?php echo $this->BcSearchBox->dispatchShowField() ?>
</p>
<div class="submit button bca-search__btns">
	<div class="bca-search__btns-item">
		<?php
			$this->BcBaser->link(
				__d('baser', '検索'),
				"javascript:void(0)",
				[
					'id' => 'BtnSearchSubmit',
					'class' => 'button bca-btn bca-btn-lg',
					'data-bca-btn-size' => 'lg',
				]
			);
		?>
	</div>
	<div class="bca-search__btns-item">
		<?php
			$this->BcBaser->link(
				__d('baser', 'クリア'),
				"javascript:void(0)",
				[
					'id' => 'BtnSearchClear',
					'class' => 'button bca-btn',
				]
			);
		?>
	</div>
</div>
<?php echo $this->Form->end() ?>
