<?php
?>
<?php echo $this->BcForm->create('BcCrudContent', ['novalidate' => true]) ?>
<?php echo $this->BcFormTable->dispatchBefore() ?>
<?php echo $this->BcForm->input('BcCrudContent.id', ['type' => 'hidden']) ?>

<section class="bca-section" data-bca-section-type="form-group">
	<div class="bca-collapse__action">
		<button type="button" class="bca-collapse__btn" data-bca-collapse="collapse" data-bca-state="" data-bca-target="#blogContentsSettingBody" aria-expanded="false" aria-controls="blogContentsSettingBody">詳細設定&nbsp;&nbsp;<i class="bca-icon--chevron-down bca-collapse__btn-icon"></i></button>
	</div>
	<div class="bca-collapse" id="blogContentsSettingBody" data-bca-state="">
		<table class="form-table bca-form-table" data-bca-table-type="type2">
			<tr>
				<th class="col-head bca-form-table__label">
					<?php echo $this->BcForm->label('BcCrudContent.list_count', __d('baser', '一覧表示件数')) ?>
					&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
				</th>
				<td class="col-input bca-form-table__input">
					<?php
						echo $this->BcForm->input(
							'BcCrudContent.list_count',
							[
								'type' => 'text',
								'size' => 20,
								'maxlength' => 255,
							]
						);
					?>&nbsp;件&nbsp;
					<i class="bca-icon--question-circle btn help bca-help"></i>
					<?php echo $this->BcForm->error('BcCrudContent.list_count') ?>
					<div id="helptextListCount" class="helptext">
						<ul>
							<li><?php echo __d('baser', '公開サイトの一覧に表示する件数を指定します。') ?></li>
							<li><?php echo __d('baser', '半角数字で入力してください。') ?></li>
						</ul>
					</div>
				</td>
			</tr>
			<tr>
				<th class="col-head bca-form-table__label">
					<?php echo $this->BcForm->label('BcCrudContent.use_category', __d('baser', 'カテゴリ機能')) ?>
				</th>
				<td class="col-input bca-form-table__input">
					<?php
						echo $this->BcForm->input(
							'BcCrudContent.use_category',
							[
								'type' => 'checkbox',
								'label' => __d('baser', '利用する'),
							]
						);
					?>
					<?php echo $this->BcForm->error('BcCrudContent.use_category') ?>
				</td>
			</tr>
			<tr>
				<th class="col-head bca-form-table__label">
					<?php echo $this->BcForm->label('BcCrudContent.use_tag', __d('baser', 'タグ機能')) ?>
				</th>
				<td class="col-input bca-form-table__input">
					<?php
						echo $this->BcForm->input(
							'BcCrudContent.use_tag',
							[
								'type' => 'checkbox',
								'label' => __d('baser', '利用する'),
							]
						);
					?>
					<?php echo $this->BcForm->error('BcCrudContent.use_tag') ?>
				</td>
			</tr>
			<tr>
				<th class="col-head bca-form-table__label">
					<?php echo $this->BcForm->label('BcCrudContent.use_content', __d('baser', '記事概要')) ?>
				</th>
				<td class="col-input bca-form-table__input">
					<?php
						echo $this->BcForm->input(
							'BcCrudContent.use_content',
							[
								'type' => 'checkbox',
								'label' => __d('baser', '利用する'),
							]
						);
					?>
					<?php echo $this->BcForm->error('BcCrudContent.use_content') ?>
				</td>
			</tr>
			<tr>
				<th class="col-head bca-form-table__label">
					<?php echo $this->BcForm->label('BlogContent.widget_area', __d('baser', 'ウィジェットエリア')) ?>
					&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
				</th>
				<td class="col-input bca-form-table__input">
					<?php
						echo $this->BcForm->input(
							'BcCrudContent.widget_area',
							[
								'type' => 'select',
								'options' => $this->BcForm->getControlsource('WidgetArea.id'),
								'empty' => __d('baser', 'サイト基本設定に従う')
							]
						);
					?>
					<i class="bca-icon--question-circle btn help bca-help"></i>
					<?php echo $this->BcForm->error('BlogContent.widget_area') ?>
					<div id="helptextWidgetArea" class="helptext">
						<?php echo __d('baser', 'コンテンツで利用するウィジェットエリアを指定します。') ?><br>
						<?php echo __d('baser', 'ウィジェットエリアはウィジェットエリア管理より追加できます。') ?><br>
						<ul>
							<li>
								<?php $this->BcBaser->link(__d('baser', 'ウィジェットエリア管理'), ['plugin' => null, 'controller' => 'widget_areas', 'action' => 'index']) ?>
							</li>
						</ul>
					</div>
				</td>
			</tr>
			<tr>
				<th class="col-head bca-form-table__label">
					<?php echo $this->BcForm->label('BcCrudContent.template', __d('baser', 'コンテンツテンプレート')) ?>
					&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
				</th>
				<td class="col-input bca-form-table__input">
					<?php
						echo $this->BcForm->input(
							'BcCrudContent.template',
							[
								'type' => 'select',
								'options' => $this->BcCrud->getTemplates($this->BcForm->value('Content.site_id')),
							]
						);
					?>
					<i class="bca-icon--question-circle btn help bca-help"></i>
					<?php echo $this->BcForm->error('BcCrudContent.template') ?>
					<div id="helptextTemplate" class="helptext">
						<ul>
							<li><?php echo __d('baser', 'コンテンツのテンプレートを指定します。') ?></li>
						</ul>
					</div>
				</td>
			</tr>
			<?php echo $this->BcForm->dispatchAfterForm('option') ?>
		</table>
	</div>
</section>

<?php echo $this->BcFormTable->dispatchAfter() ?>

<!-- button -->
<div class="submit">
	<div class="bca-actions__main">
		<?php
			echo $this->BcForm->submit(__d('baser', '保存'), [
				'div' => false,
				'class' => 'button bca-btn bca-actions__item',
				'id' => 'BtnSave',
				'data-bca-btn-type' => 'save',
				'data-bca-btn-size' => 'lg',
				'data-bca-btn-width' => 'lg'
			]);
		?>
	</div>
</div>

<?php echo $this->BcForm->end() ?>
