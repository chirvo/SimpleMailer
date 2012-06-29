<?php
$this->breadcrumbs=array(
	'Simple Mailer Templates'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SimpleMailerTemplate', 'url'=>array('index')),
	array('label'=>'Manage SimpleMailerTemplate', 'url'=>array('admin')),
);
?>

<h1>Create SimpleMailerTemplate</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>