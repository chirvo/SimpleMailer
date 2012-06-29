<?php

/**
 * This is the model class for table "sm_queue".
 *
 * The followings are the available columns in table 'sm_queue':
 * @property integer $id
 * @property string $to
 * @property string $subject
 * @property string $body
 * @property string $headers
 * @property integer $status
 */
class SimpleMailerQueue extends CActiveRecord
{
	const STATUS_NOT_SENT = 0;
	const STATUS_SENT = 1;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SimpleMailQueue the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'sm_queue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('to, subject, body, headers, status', 'required'),
			array('status', 'numerical', 'integerOnly' => true),
			array('to', 'length', 'max' => 255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, to, status', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'to' => 'To',
			'subject' => 'Subject',
			'body' => 'Body',
			'headers' => 'Headers',
			'status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('to', $this->to, true);
		$criteria->compare('status', $this->status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function behaviors() {
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
			)
		);
	}

	/**
	 * @static
	 * @return int the number of mails not sent
	 */
	public static function getNotSentCount() {
		return (int)self::model()->countByAttributes(array(
			'status' => self::STATUS_NOT_SENT,
		));
	}

	/**
	 * @static
	 * @return int the number of sent mail
	 */
	public static function getSentCount() {
		return (int)self::model()->countByAttributes(
			array(
				'status' => self::STATUS_SENT,
			),
			'DATE(create_time)="' . date('Y-m-d') . '"'
		);
	}
}
