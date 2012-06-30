<?php
class {ClassName} extends CDbMigration {
	public function up() {
		$this->createTable('sm_template', array(
			'id' => 'pk',
			'name' => 'string NOT NULL',
			'description' => 'string NULL',
			'from' => 'string NULL',
			'subject' => 'string NULL',
			'body' => 'text NULL',
			'alternative_body' => 'text NULL',
		));
		$this->createIndex('idx_sm_template_name', 'sm_template', 'name', true);

		$this->createTable('sm_queue', array(
			'id' => 'pk',
			'to' => 'string NOT NULL',
			'subject' => 'string NOT NULL',
			'body' => 'text NOT NULL',
			'headers' => 'text NOT NULL',
			'status' => 'integer NOT NULL',
			'create_time' => 'timestamp NULL',
			'update_time' => 'timestamp NULL'
		));
		$this->createIndex('idx_sm_queue_to', 'sm_queue', 'to', false);
		$this->createIndex('idx_sm_queue_subject', 'sm_queue', 'subject', false);
		$this->createIndex('idx_sm_queue_status', 'sm_queue', 'status', false);

		$this->createTable('sm_list', array(
			'id' => 'pk',
			'name' => 'string NOT NULL',
			'description' => 'string NULL',
			'query' => 'text NOT NULL',
			'email_field' => 'string NOT NULL',
		));
		$this->createIndex('idx_sm_list_name', 'sm_list', 'name', true);
	}

	public function down() {
		$this->dropIndex('idx_sm_template_name', 'sm_template');
		$this->dropIndex('idx_sm_list_name', 'sm_list');
		$this->dropIndex('idx_sm_queue_to', 'sm_queue');
		$this->dropIndex('idx_sm_queue_subject', 'sm_queue');
		$this->dropIndex('idx_sm_queue_status', 'sm_queue');
		$this->dropTable('sm_template');
		$this->dropTable('sm_queue');
		$this->dropTable('sm_list');
	}
}
