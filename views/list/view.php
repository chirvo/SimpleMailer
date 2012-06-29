<?php
$this->breadcrumbs=array(
	'Simple Mailer Lists'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List SimpleMailerList', 'url'=>array('index')),
	array('label'=>'Create SimpleMailerList', 'url'=>array('create')),
	array('label'=>'Update SimpleMailerList', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SimpleMailerList', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SimpleMailerList', 'url'=>array('admin')),
);
?>

<h1>View SimpleMailerList #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'query',
		'email_field',
	),
)); ?>
