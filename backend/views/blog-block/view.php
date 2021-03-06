<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 *
 * @var yii\web\View $this
 * @var common\models\blog\BlogBlock $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Blog Block');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Blog Blocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud blog-block-view">

	<!-- flash message -->
	<?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
		<span class="alert alert-info alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
			<?php echo \Yii::$app->session->getFlash('deleteError') ?>
		</span>
	<?php endif; ?>

	<h1>
		<?php echo Yii::t('models', 'Blog Block') ?>
		<small>
			<?php echo Html::encode($model->name) ?>
		</small>
	</h1>


	<div class="clearfix crud-navigation">

		<!-- menu buttons -->
		<div class='pull-left'>
			<?php echo Html::a(
				'<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
				['update', 'id' => $model->id],
				['class' => 'btn btn-info']
			) ?>

			<?php echo Html::a(
				'<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
				['create', 'id' => $model->id, 'BlogBlock' => $copyParams],
				['class' => 'btn btn-success']
			) ?>

			<?php echo Html::a(
				'<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'),
				['create'],
				['class' => 'btn btn-success']
			) ?>
		</div>

		<div class="pull-right">
			<?php echo Html::a('<span class="glyphicon glyphicon-list"></span> '
				. Yii::t('cruds', 'Full list'), ['index'], ['class' => 'btn btn-default']) ?>
		</div>

	</div>

	<hr />


	<?php echo DetailView::widget([
		'model' => $model,
		'attributes' => [
			'name',
			'alias',
			// 'template:ntext',
			'inputs:ntext',
			'type',
			// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::attributeFormat
			[
				'format' => 'html',
				'attribute' => 'created_by',
				'value' => ($model->createdBy ?
					Html::a('<i class="glyphicon glyphicon-list"></i>', ['/user/index']) . ' ' .
					Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> ' . $model->createdBy->id, ['/user/view', 'id' => $model->createdBy->id,]) . ' ' .
					Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'BlogBlock' => ['created_by' => $model->created_by]])
					:
					'<span class="label label-warning">?</span>'),
			],
			// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::attributeFormat
			[
				'format' => 'html',
				'attribute' => 'updated_by',
				'value' => ($model->updatedBy ?
					Html::a('<i class="glyphicon glyphicon-list"></i>', ['/user/index']) . ' ' .
					Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> ' . $model->updatedBy->id, ['/user/view', 'id' => $model->updatedBy->id,]) . ' ' .
					Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'BlogBlock' => ['updated_by' => $model->updated_by]])
					:
					'<span class="label label-warning">?</span>'),
			],
		],
	]); ?>


	<hr />

	<?php echo Html::a(
		'<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('cruds', 'Delete'),
		['delete', 'id' => $model->id],
		[
			'class' => 'btn btn-danger',
			'data-confirm' => '' . Yii::t('cruds', 'Are you sure to delete this item?') . '',
			'data-method' => 'post',
		]
	); ?>

</div>