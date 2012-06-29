<?php
/**
 * cron class file.
 * For running this from console simply type './yiic cron' from the protected/ directory
 */

class MailerCommand extends CConsoleCommand
{
	/**
	 * Executes the command.
	 * @param array $args command line parameters for this command.
	 * @return int 0 on success, 1 on error.
	 */
	public function run($args) {
		try {
			$logger = Yii::getLogger();
			$logger->autoFlush = 1;
			$logger->detachEventHandler('onFlush', array(Yii::app()->log, 'collectLogs'));
			$logger->attachEventHandler('onFlush', array($this, 'processLogs'));

			echo 'Limit: ' . Yii::app()->getModule('SimpleMailer')->sendEmailLimit . "\n";
			/**
			 * @var $emails SimpleMailerQueue
			 */
			$emails = SimpleMailerQueue::model()->findAllByAttributes(
				array(
					'status' => SimpleMailerQueue::STATUS_NOT_SENT,
				),
				array(
					'limit' => Yii::app()->getModule('SimpleMailer')->sendEmailLimit,
				)
			);

			echo 'Count: ' . count($emails) . "\n";

			if (!$emails) {
				Yii::log(Yii::t('mailer', 'No emails in queue. Exiting.'), 'error', 'application.commands.MailerCommand');
				exit(0);
			}

			foreach ($emails as $email) {
				if (mail($email->to, $email->subject, $email->body, $email->headers)) {
					$email->status = SimpleMailerQueue::STATUS_SENT;
					$email->save();
					echo 'Sent: ' . $email->to . "\n";
				}
			}
			exit(0);
		}
		catch (Exception $e) {
			Yii::log($e->getMessage(), 'error', 'application.commands.MailerCommand');
			exit(1);
		}
	}

	public function processLogs($event) {
		static $routes;
		$logger = Yii::getLogger();
		$routes = isset($routes) ? $routes : Yii::app()->log->getRoutes();
		foreach ($routes as $route) {
			if ($route->enabled) {
				$route->collectLogs($logger, true);
				$route->logs = array();
			}
		}
	}

	/**
	 * Provides the command description.
	 * This method may be overridden to return the actual command description.
	 * @return string the command description. Defaults to 'Usage: php entry-script.php command-name'.
	 */
	public function getHelp() {
		return 'Usage: yiic mailer - without parameters';
	}
}
