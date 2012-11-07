SimpleMailer - A KISS* solution for emailing your users.
========================================================

SimpleMailer is a module that helps the administrator on creating and delivering emails from your application. It is created because the need of a simple emailing system tightly integrated to the Yii apps I develop. After many unsuccessful tries to integrate PHPList to my apps I decided to write a solution that can fit my shoes. This way I don't need to:

- Try to make a skin/theme for PHPList that mimics the one I use in my app.
- I do not have to deal with keeping my database and the PHPList's one synchronized.
- Spend countless hours hardcoding the email preparation and sending in my application controllers every time I need to send an email, making them eventually unreadable.

So, I wrote this to make me a happier programmer. I hope this module can make you happier too.


What is this contained in this module:
======================================

A Template system:
------------------
The template system allows you to create multiple email templates that are going to be sent from your application.

Mail Queue:
-----------
With SimpleMailer you can always send your emails just after building them or enqueue them for later delivery.

Kind-of-Mailing-List creator:
-----------------------------
A very simplistic Mailing list creator. Basically it allows you to filter your database to select the desired users by using a SQL query.


What SimpleMailer is not:
=========================

- A HTML template editor. You can use any HTML editor for building your HTML templates. Remember to user either XHTML 1.0 or HTML 4.0 (Put the soundtrack of 'Back to the Future', take a soda and start coding like in the 90s).
- A comprehensive mailing list application. If you need one of those try MailChimp or PHPList.
- A sophisticated email sending library. This module uses the PHP mail() function for delivering emails. So you must configure properly your MTA. The reason to use mail() instead of a more sophisticated library like SwiftMailer or PHPMailer is because I wanted this module to be lean. No bloated dependencies other than Yii itself.


Installation instructions
=========================

- Download the ZIP file and unpack it into the protected/modules directory. If the directory does not exists create it.

- Execute the following commands:

```bash
cd /your/app/directory/protected
./yiic migrate create --templateFile=application.modules.mailer.migrations.template add_simplemailer_tables
./yiic migrate up
cp modules/mailer/commands/MailerCommand.php commands/
```

- Run 'crontab -e' and add the Mailer command:

```crontab
0,30 * * * * /path/to/your/application/protected/yiic mailer #Send emails in queue every 30 minutes
```

- In your protected/config/main.php and protected/config.console.php add the following lines:

```php
'import' => array(
		...
		'application.modules.mailer.components.*',
		'application.modules.mailer.models.*',
		...
		),
	'modules' => array(
			...
			'mailer' => array(
				// This is the default value, for attaching the images used into the emails.
				'attachImages' => true,
				// Also the default value, how much emails should be sent when calling yiic mailer
				'sendEmailLimit'=> 500,
				),
			...
			),
```
	- Now access SimpleMailer via http://your_app_ip_or_domain/mailer/. You're done.


	Usage:
	======

	The workflow of SimpleMailer can be resumed in these 10 simple steps:

	- *Step 1:* Create an email template: You can create it using any WYSIWYG HTML tool like Kompozer. Also you can use the MailChimp templates freely available at their site.
	- *Step 2:* Preview it in your HTML web browser. If it looks good for you then source it (by pressing Ctrl+U in your browser window). Select it (Ctrl+A) and copy it (Ctrl+C).
	- *Step 3:* Go to http://your_app_ip_or_domain/mailer/ and click on 'Create Template'.
	- *Step 4:* Fill in the form. The 'Name' field is important since you're gonna access this template by it.
	- *Step 5:* Select the 'Body' field and paste what you previously copied (Ctrl+V).
	- *Step 6:* Fill in the 'Alternative body' field with a text-only version of your email.
	- *Step 7:* Click the 'Save' button.
	- *Step 8:* Go to your desired controller action and write down the following lines:

	```php
	$template_vars = array(
			//Put any variables you need to replace. the suggested format is '__KEY__' => 'value'. More about this below.
			'__username__' => 'John Doe',
			'__quote__' => 'Roses are red, Violets are blue. Sugar is sweet, Who the hell are you?',
			);
//If you want to enqueue the email for later sending just call Mailer::enqueue() instead. Same params, please.
//For list email sending read below.
Mailer::send('johndoe@example.com', 'template_name', $template_vars);
```

- *Step 9:* Now access your action from your browser. After execution John Doe will receive a personalized email with a quote
for him.
- *Step 10:* Congratulate yourself. You made it. :)


About templates:
================
There's no specific rules to set your template variables in your templates except that the characters used must be in the [a-zA-Z0-9_] range (that means numbers, letters, and underscore).

I use the *'__placeholder__'* format (two underscores, the variable name in lowercase and two underscores more), however you can use any other format you like if it meets the range requirement (010101VARIABLE010101 will do it but it is unreadable. You got the point).

Whatever format you choose to use, keep in mind that you need to pass your '__variables__' => 'value' array to the Mailer::send() or Mailer::enqueue() function.

The module also allows you to add dynamic generated content to the template. This is done using another array with the same format considerations explained above, called 'Template Partials'. Let's explain this with an example. Suppose Clark Kent and Lois Lane are subscribed to the daily offers that appear on the Daily Planet (you're their web developer, of course). Then your code should look like this:

```php
//This should return Lois and Clark.
$users = User::model()->findByAttributes(array(
			'subscribed' => '1',
			));

$template_partials = array(
		//You already programmed the function below. It spits out HTML.
		'__todaysOffers__' => Offer::buildPartialForOffersMail();
		);

foreach ($users as $user)  {
	//The user info for this example is stored in a 'Profile' object.
	$template_vars = array(
			'__username__' => $user->profile->firstName . ' ' . $user->profile->lastName;
			);
	//You can also enqueue emails
	Mailer::enqueue($user->profile->email, 'template_offers', $template_vars, $template_partials);
}
```

Of course, there's a more practical way to achieve this. And now, enter the 'Lists'.


That Kind-of-Mailing-List thing:
================================
Well, it's not a full-fledged mailing list management system. It's more like a "send this email to all these folks" that get the users emails using a SQL sentence. For the simplicity of this document I'll refer to this as a 'List'.

Creating a new List:
--------------------

Follow these steps:

- Connect to your SQL server and test the SQL sentence you want to use. An example could be:

```sql
SELECT email, name, quote FROM profile WHERE location='atlantis';
```

- If it worked as expected, copy it.
- Go to http://your_app_ip_or_domain/mailer/ and click on 'Create Mailing List'.
- Fill in the form. The 'Name' field is important since you're gonna access this List by it. Don't forget to paste your SQL sentence (you can omit the semicolon at the end of the sentence). You can get any data with this SQL sentence. This data can be substituted as template variables (Read the FAQ below). It is important also to specify the name of the column containing the emails. If you mispell it the
 script will simply blow up in your face. ;-)
- Go to your desired controller action and write down the following lines:

```php
//Mailer::sendToList() enqueues all the messages being sent.
//The $template_vars are generated with the output of the SQL sentence.
Mailer::sendToList('list_name', 'template_name', $template_partials);
```

- Now access your action from your browser. After execution your emails will be enqueued and will eventually being sent.

FAQ:
====

Q: I followed all the steps to install this module but I can't send email. Why?

A: Be sure you (or the System Administrator of the company you work for) configured properly a MTA in your server (lets say Postfix). This needs a little knowledge of system administration but as usual you can google the steps to do such configuration. This also will improve the response time of your PHP scripts when sending email.

 You won't be able to send emails until you configure your MTA. Period.


Q: I followed the install instructions to the letter but I can't access it via
http://your_app_ip_or_domain/SimpleMailer. What's wrong?

A: This could happen if you use te version 0.2 or below of SimpleMailer. If this is your case, Be sure that you didn't set the "caseSensitive" parameter of the "urlManager" component to 'false' in your configuration. If you did it, you won't access SimpleMailer the usual way. The solutions for this issue are: a) set "caseSensitive" to 'true' (the default value) or b) rename the SimpleMailer directory and change all the Yii path routes in the code. Option a) is easier. If neither a) or b) are solutions for you then please c) use the latest version of SimpleMailer.

A special thanks to yugene@Yii forums for pinpoint and solve the issue.


Q: What's the difference between a template variable and a template partial?

A: A template partial is a piece of HTML code that is going to be substituted ONCE, when compiling a template. It's a dynamic text that you want to change every time you send an email to a bunch of people without modifying the entire template. I.E. the today's offers that changes every day and you want to keep all members of your list up to date. It's the message you want to communicate.

Template variables are related to the user who is receiving the email. This variables are used for personalizing the email with specific information of the user.


Q: How can I pass template variables to a list?

A: OK, I got two news for you, one good and one bad. The bad is you can't. The good is (assuminng you're using SimpleMailer version 0.3 or above) the system will generate them for you. This is how it works:

 Mailer::sendToList() executes the SQL sentence you wrote. Then it builds the $to[] array containing all the emails. Also it will build the $template_vars[] array containing in it the associative arrays with the information of each user. The array index in $to[] should match the array index in $template_vars[]. Then both arrays are passed to Mailer::queue().

 Thanks to grod@Yii forums for giving me the idea to solve the $template_vars issue when using lists.


Q: How can I reset the sm_queue table?

A: I didn't execute these SQL commands yet, however they should work (uncle Google told me):


```sql
	TRUNCATE sm_queue;
```


This will erase all your queued emails. You've been warned.


Q: I think I've found a bug. What should I do?

A: Fix it, make a patch and send it to me. :) Or at least report it in the SimpleMailer extension page. I will try to fix it as soon as I can. The same thing goes to suggestions or improvements.


Q: Why is your English so funny?

A: I'm not a native English speaker. I do speak some English, but my native language is Spanish. And if you think my English is funny you have to hear me trying to utter words in German or Russian. You will laugh your arse off. For sure.


Q: Do you have Twitter/Facebook/Email?

A: Yes I do.


Q: I have another question regarding this extension that is not in this FAQ. How can I contact you?

A: You can leave me a message here or at the Yii Forum SimpleMailer page.

