<?php
/**
 * Mailer component
 * User: bigchirv
 * Date: 5/4/12
 * Time: 10:35 PM
 */
class Mailer extends CComponent
{
	/**
	 * Heavily based on http://stackoverflow.com/questions/1606588/how-to-attach-and-show-image-in-mail-using-php and
	 * http://www.phpeveryday.com/articles/PHP-Email-Using-Embedded-Images-in-HTML-Email-P113.html
	 * @param MailerTemplate $template the template instance
	 * @param array $template_partials HTML code to be inserted in the email. array('__key_for_partial__' => '<h1>html</h1>').
	 *        Defaults to array();
	 * @return array Contents: array( 'multipart'=> string multipart_data, 'header' => string mail_header)
	 * @throws CException
	 */
	public static function compileMultipartTemplate($template, $template_partials = array()) {
		$attach_images = Yii::app()->getModule('mailer')->attachImages;
		if (!is_a($template, 'MailerTemplate')) {
			throw new CException('Mailer::compileMultipartTemplate(): '. Yii::t(
				'mailer',
				'Wrong object passed, expected MailerTemplate instance.'
			));
		}
		if (is_array($template_partials) && !empty($template_partials))
			$html = strtr($template->body, $template_partials);
		else
			$html = $template->body;

		$boundary = md5(uniqid(time()));

		// Substitute every url for img tags with a cid in $html
		if ($attach_images) {
			$paths = array();
			preg_match_all('~<img.*?src=.([\/.a-z0-9:_-]+).*?>~si', $html, $matches);
			foreach ($matches[1] as $img) {
				if (strpos($img, "http://") == false) {
					$content_id = md5($img);
					$url = parse_url($img);
					$paths[] = array(
						'path' => $_SERVER['DOCUMENT_ROOT'] . $url['path'],
						'cid' => $content_id,
					);
					$html = str_replace($img, 'cid:' . $content_id, $html);
				}
			}
		}

		// Multipart header
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-$boundary\"\r\n";
		$headers .= "From: " . $template->from . "\r\n";
		$headers .= "X-Sender-IP: $_SERVER[SERVER_ADDR]\r\n";
		$headers .= 'Date: ' . date('n/d/Y g:i A') . "\r\n";

		// Text-only body
		$multipart = "--PHP-alt-$boundary\n";
		$multipart .= "Content-Type: text/plain; charset=utf-8\n";
		$multipart .= "$template->alternative_body\n\n";

		$multipart .= "--PHP-alt-$boundary\n";
		$multipart .= "Content-Type: multipart/related; boundary=\"PHP-related-$boundary\"\n\n";

		// HTML body
		$multipart .= "--PHP-related-$boundary\n";
		$multipart .= "Content-Type: text/html; charset=utf-8\n";
		$multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n";
		$multipart .= "$html\n\n";

		// Images as attachment
		if ($attach_images) {
			foreach ($paths as $path) {
				if (file_exists($path['path']))
					$fp = fopen($path['path'], "r");
				if (!$fp) {
					throw new CException('Mailer::compileMultipartTemplate(): '. Yii::t(
						'mailer',
						'Cannot open file ')
					. $path['path']);
				}

				$image_type = substr(strrchr($path['path'], '.'), 1);
				$file = fread($fp, filesize($path['path']));
				fclose($fp);

				$message_part = '';

				switch ($image_type) {
					case 'png':
					case 'PNG':
						$message_part .= "Content-Type: image/png";
						break;
					case 'jpg':
					case 'jpeg':
					case 'JPG':
					case 'JPEG':
						$message_part .= "Content-Type: image/jpeg";
						break;
					case 'gif':
					case 'GIF':
						$message_part .= "Content-Type: image/gif";
						break;
				}

				$message_part .= "; file_name = \"" . $path['path'] . "\"\n";
				$message_part .= 'Content-ID: <' . $path['cid'] . ">\n";
				$message_part .= "Content-Transfer-Encoding: base64\n";
				$message_part .= "Content-Disposition: inline; filename = \"" . basename($path['path']) . "\"\n\n";
				$message_part .= chunk_split(base64_encode($file)) . "\n";
				$multipart .= "--PHP-related-$boundary\n" . $message_part . "\n";
			}
			$multipart .= "--PHP-related-$boundary\n\n";
		}
		// Closing compiled email template
		$multipart .= "--PHP-alt-$boundary--\n";

		return array('multipart' => $multipart, 'headers' => $headers);
	}

	/**
	 * @static
	 * @param $to
	 * @param $template_name
	 * @param array $template_vars
	 * @param array $template_partials
	 * @param string $action
	 * @return bool
	 * @throws CException
	 */
	private static function process($to, $template_name, $template_vars = array(), $template_partials = array(), $action = 'send') {
		if (is_string($to)) {
			$to = array($to);
		}
		/** @var $template MailerTemplate */
		$template = MailerTemplate::model()->findByAttributes(array(
			'name' => $template_name,
		));
		if (!$template) {
			throw new CException(Yii::t('mailer', 'Template does not exists.'));
		}

		//Template compilation
		$compiled_template = self::compileMultipartTemplate($template, $template_partials);

		// If $template_vars is an associative array, we 'fix' it putting it into a numeric array
		// Code taken from http://php.net/manual/en/function.is-array.php, Anonymous comment on 2009-05-16
		if(is_array($template_vars) && !empty($template_vars) && 0 !== count(array_diff_key($template_vars, array_keys(array_keys($template_vars))))) {
			$cool_swap_array[] = $template_vars;
			$template_vars = $cool_swap_array;
		}

		$statuses = array();
		$i = 0;
		foreach ($to as $receiver) {
			//Substitute the template variables with actual data
			if ($template_vars)
				$body = strtr($compiled_template['multipart'], $template_vars[$i]); //$template_vars[$i] should always match to $to[$i]
			else
				$body = $compiled_template['multipart'];

			switch ($action) {
				case 'send':
					$statuses[$receiver] = mail($receiver, $template->subject, $body, $compiled_template['headers']);
					break;
				case 'enqueue':
					$queue = new MailerQueue;
					$queue->to = $receiver;
					$queue->subject = $template->subject;
					$queue->body = $body;
					$queue->headers = $compiled_template['headers'];
					$queue->status = MailerQueue::STATUS_NOT_SENT;
					$statuses[$receiver] = $queue->save();
					break;
				default:
					break;
			}
			$i++;
		}
		foreach ($statuses as $email => $status) {
			if (!$status) {
				Yii::log(Yii::t('app', 'Failed delivering some emails.'), 'error');
				return false;
			}
		}
		return true;
	}

	/**
	 * @static
	 * @param $to
	 * @param $template_name
	 * @param array $template_vars
	 * @param array $template_partials
	 * @return bool
	 */
	public static function enqueue($to, $template_name, $template_vars = array(), $template_partials = array()) {
		return self::process($to, $template_name, $template_vars, $template_partials, 'enqueue');
	}

	/**
	 * @static
	 * @param $to
	 * @param $template_name - The MailerTemplate template name
	 * @param array $template_vars - '__key__' => 'value' to be replaced
	 * @param array $template_partials - '__key__' => 'HTML code' to be replaced into the email template
	 * @return bool
	 */
	public static function send($to, $template_name, $template_vars = array(), $template_partials = array()) {
		return self::process($to, $template_name, $template_vars, $template_partials, 'send');
	}

	/**
	 * @static
	 * @param $list_name - The MailerList list name
	 * @param $template_name - The MailerTemplate template name
	 * @param array $template_partials - '__key__' => 'HTML code' to be replaced into the email template
	 * @throws CException
	 */
	public static function sendToList($list_name, $template_name, $template_partials = array()) {
		$db = Yii::app()->db;
		$list = MailerList::model()->findByAttributes(array(
			'name' => $list_name,
		));
		if (!$list)
			throw new CException('Mailer::sendToList(): ' . Yii::t('mailer', "List doesn't exists."));

		try {
			$to = array();
			$template_vars = array();
			$results = $db->createCommand($list->query)->queryAll();
			foreach ($results as $result) {
				$to[] = $result[$list->email_field];
				$template_vars_iteration = array();
				foreach (array_keys($result) as $key) {
					// We're forcing the '__placeholder__' format. I need a way to make this more flexible.
					// Suggestions are welcomed.
					$template_vars_iteration['__' . $key . '__'] = $result[$key];
				}
				$template_vars[] = $template_vars_iteration;
			}
			self::enqueue($to, $template_name, $template_vars, $template_partials);
		} catch (Exception $e) {
			throw new CException("Mailer::sendToList(): " . $e->getMessage());
		}
	}
}
