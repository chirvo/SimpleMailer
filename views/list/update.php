<?php
$this->breadcrumbs=array(
	'Simple Mailer Lists'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SimpleMailerList', 'url'=>array('index')),
	array('label'=>'Create SimpleMailerList', 'url'=>array('create')),
	array('label'=>'View SimpleMailerList', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SimpleMailerList', 'url'=>array('admin')),
);
?>

<h1>Update SimpleMailerList <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>