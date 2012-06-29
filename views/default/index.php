<?php
$this->breadcrumbs=array(
	$this->module->id,
);

$this->menu=array(
	array('label'=>'Manage Templates', 'url'=>array('template/index')),
	array('label'=>'Manage Queue', 'url'=>array('queue/index')),
	array('label'=>'Manage Lists', 'url'=>array('list/index')),
);
?>
<h1>Simple Mailer Dashboard</h1>
<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th>Info</th>
			<th>Value</th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td>Templates in database:</td>
		<td><?php echo SimpleMailerTemplate::model()->count(); ?></td>
	</tr>
	<tr>
		<td>Mails sent today:</td>
		<td><?php echo SimpleMailerQueue::getSentCount(); ?></td>
	</tr>
	<tr>
		<td>Mails not sent (still in queue):</td>
		<td><?php echo SimpleMailerQueue::getNotSentCount(); ?></td>
	</tr>
	</tbody>
</table>
