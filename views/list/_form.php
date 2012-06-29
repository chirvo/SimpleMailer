<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'simple-mailer-list-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'query'); ?>
		<?php echo $form->textArea($model,'query',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'query'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email_field'); ?>
		<?php echo $form->textField($model,'email_field',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email_field'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->