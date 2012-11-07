<?php
$this->breadcrumbs=array(
	'SimpleMailer Mailing Lists'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Mailing Lists', 'url'=>array('index')),
	array('label'=>'Manage Mailing Lists', 'url'=>array('admin')),
);
?>

<h1>Create Mailing List</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
