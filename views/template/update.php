<?php
$this->breadcrumbs=array(
	'Simple Mailer Templates'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SimpleMailerTemplate', 'url'=>array('index')),
	array('label'=>'Create SimpleMailerTemplate', 'url'=>array('create')),
	array('label'=>'View SimpleMailerTemplate', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SimpleMailerTemplate', 'url'=>array('admin')),
);
?>

<h1>Update SimpleMailerTemplate <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>