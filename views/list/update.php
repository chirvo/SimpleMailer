<?php
$this->breadcrumbs=array(
	'Simple Mailer Lists'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Mailer Lists', 'url'=>array('index')),
	array('label'=>'Create Mailer List', 'url'=>array('create')),
	array('label'=>'View Mailer List', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Mailer Lists', 'url'=>array('admin')),
);
?>

<h1>Update SimpleMailerList <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
