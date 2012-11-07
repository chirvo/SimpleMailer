<?php
$this->breadcrumbs=array(
	'SimpleMailer Mailing Lists'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Mailing Lists', 'url'=>array('index')),
	array('label'=>'Create Mailing List', 'url'=>array('create')),
	array('label'=>'Update Mailing List', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Mailing List', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Mailing Lists', 'url'=>array('admin')),
);
?>

<h1>View Mailing List #<?php echo $model->id; ?></h1>

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
