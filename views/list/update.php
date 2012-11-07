<?php
$this->breadcrumbs=array(
	'Simple Mailer Lists'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Mailing Lists', 'url'=>array('index')),
	array('label'=>'Create Mailing List', 'url'=>array('create')),
	array('label'=>'View Mailing List', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Mailing Lists', 'url'=>array('admin')),
);
?>

<h1>Update Mailing List <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
