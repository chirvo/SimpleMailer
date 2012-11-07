<?php
$this->breadcrumbs=array(
	'SimpleMailer Templates'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Templates', 'url'=>array('index')),
	array('label'=>'Manage Templates', 'url'=>array('admin')),
);
?>

<h1>Create Template</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
