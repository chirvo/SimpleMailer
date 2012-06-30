<?php
$this->breadcrumbs = array(
	'Simple Mailer Queues' => array('index'),
	$model->id,
);

$this->menu = array(
	array('label' => 'Delete Queued Element', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
	array('label' => 'Manage Queue', 'url' => array('admin')),
);
?>

<h1>View SimpleMailerQueue #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
		'id',
		'to',
		'subject',
		'status',
	),
)); ?>
