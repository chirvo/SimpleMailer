<?php

class DefaultController extends Controller
{
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
			array(
				'allow',
				'actions' => array(
					'index',
					'test'
				),
				'users' => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionIndex() {
		$this->render('index');
	}

	public function actionTest() {
		echo SimpleMailer::send('bigchirv@gmail.com', 'admin');
	}
}