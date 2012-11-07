<?php
$this->breadcrumbs=array(
	'SimpleMailer Mailing Lists',
);

$this->menu=array(
	array('label'=>'Create Mailing List', 'url'=>array('create')),
	array('label'=>'Manage Mailing Lists', 'url'=>array('admin')),
);
?>

<h1>Mailing Lists</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
