<?php
$this->breadcrumbs=array(
	'Simple Mailer Templates'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Templates', 'url'=>array('index')),
	array('label'=>'Create Template', 'url'=>array('create')),
	array('label'=>'Update Template', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Template', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Templates', 'url'=>array('admin')),
);
?>

<h1>View Template #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'from',
		'subject',
	),
)); ?>

<h4>Send Preview</h4>
<div class="campaign-admin">
	<?php echo CHtml::beginForm('../../sendPreview' , 'get'); ?>
	<?php echo CHtml::hiddenField('id', $model->id); ?>
	<?php echo CHtml::hiddenField('template', $model->name); ?>
	<?php echo CHtml::label(Yii::t('app', 'Email'), 'email')?>
	<?php echo CHtml::textField('email'); ?>
	<?php echo CHtml::checkBox('sure', false); ?><span><?php echo Yii::t('app', "I'm sure I want to send it"); ?></span>
	<?php echo CHtml::submitButton('Send test', array('class' => 'btn btn-success', 'disabled' => 'disabled', 'id' => 'sendmail')); ?>
	<br>
	<?php echo CHtml::endForm(); ?>
</div>

<h4>HTML Preview</h4>
<iframe width="650" height="650" style="border: 5px solid #fcfcfc; margin-left: 20px;" src="<?php echo $this->createUrl('preview', array('id' => $model->id)) ?>"></iframe>

<script type="text/javascript">
	$('#sure').on('click', function () {
		if ($(this).is(':checked')) {
			$('#sendmail').removeAttr('disabled');
		} else {
			$('#sendmail').attr('disabled', 'disabled');
		}
	});
</script>

