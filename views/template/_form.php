<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'simple-mailer-template-form',
	'enableAjaxValidation' => false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255)); ?>
		<?php echo $form->error($model, 'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'description'); ?>
		<?php echo $form->textField($model, 'description', array('size' => 120, 'maxlength' => 255, 'style' => 'width: 665px;')); ?>
		<?php echo $form->error($model, 'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'from'); ?>
		<?php echo $form->textField($model, 'from', array('size' => 120, 'maxlength' => 255, 'style' => 'width: 400px;')); ?>
		<?php echo $form->error($model, 'from'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'subject'); ?>
		<?php echo $form->textField($model, 'subject', array('size' => 120, 'maxlength' => 255, 'style' => 'width: 665px;')); ?>
		<?php echo $form->error($model, 'subject'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'body'); ?>
		<?php echo $form->textArea($model, 'body', array('rows' => 15, 'cols' => 50, 'style' => 'width: 665px;')); ?>
		<?php echo $form->error($model, 'body'); ?>
		<?php
/*		$this->widget('ext.elrte.elRTE', array(
			'selector' => 'SimpleMailerTemplate_body',
			'height' => '600',
			'width' => '680',
			'absoluteURLs' => true,
			'toolbar' => 'maxi',
		));*/
		?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model, 'alternative_body'); ?>
		<?php echo $form->textArea($model, 'alternative_body', array('rows' => 15, 'cols' => 50, 'style' => 'width: 665px;')); ?>
		<?php echo $form->error($model, 'alternative_body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->