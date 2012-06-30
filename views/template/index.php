<?php
$this->breadcrumbs=array(
	'Simple Mailer Templates',
);

$this->menu=array(
	array('label'=>'Create Template', 'url'=>array('create')),
	array('label'=>'Manage Templates', 'url'=>array('admin')),
);
?>

<h1>Simple Mailer Templates</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
