<?php
$this->breadcrumbs=array(
	'Simple Mailer Lists'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SimpleMailerList', 'url'=>array('index')),
	array('label'=>'Manage SimpleMailerList', 'url'=>array('admin')),
);
?>

<h1>Create SimpleMailerList</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>