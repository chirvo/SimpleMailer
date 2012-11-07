<?php
$this->breadcrumbs=array(
	'SimpleMailer Templates'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Templates', 'url'=>array('index')),
	array('label'=>'Create Template', 'url'=>array('create')),
	array('label'=>'View Template', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Templates', 'url'=>array('admin')),
);
?>

<h1>Update Template #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
