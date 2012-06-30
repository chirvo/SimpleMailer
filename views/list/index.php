<?php
$this->breadcrumbs=array(
	'Simple Mailer Lists',
);

$this->menu=array(
	array('label'=>'Create Mailer List', 'url'=>array('create')),
	array('label'=>'Manage Mailer Lists', 'url'=>array('admin')),
);
?>

<h1>Simple Mailer Lists</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
