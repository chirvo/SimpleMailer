SimpleMailer - A KISS* solution for emailing your users.
========================================================

SimpleMailer is a module that helps the administrator on creating and delivering emails from your application. It is
created because the need of a simple emailing system tightly integrated to the Yii apps I develop. After many
unsuccessful tries to integrate PHPList to my apps I decided to write a solution that can fit my shoes. This way I
don't need to:

- Try to make a skin/theme for PHPList that mimics the one I use in my app.
- I do not have to deal with keeping my database and the PHPList's one synchronized.
- Spend countless hours hardcoding the email preparation and sending in my application controllers every time I need
  to send an email, making them eventually unreadable.

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
A very simplistic Mailing list creator. Basically it allows you to filter your database to select the desired
users by using a SQL query.


What SimpleMailer is not:
=========================

- A HTML template editor. You can use any HTML editor for building your HTML templates. Remember to user either
  XHTML 1.0 or HTML 4.0 (Put the soundtrack of 'Back to the Future', take a soda and start coding like in the 90s).
- A comprehensive mailing list application. If you need one of those try MailChimp or PHPList.
- A sophisticated email sending library. This module uses the PHP mail() function for delivering emails. So you must
  configure properly your MTA. The reason to use mail() instead of a more sophisticated library like SwiftMailer or
  PHPMailer is because I wanted this module to be lean. No bloated dependencies other than Yii itself.


Installation instructions
=========================

- Download the ZIP file and unpack it into the protected/modules directory. If the directory does not exists create it.

- Execute the following commands:

    cd /your/app/directory/protected
    ./yiic migrate create --templateFile=application.modules.SimpleMailer.migrations.template add_simplemailer_tables
    ./yiic migrate up
    cp modules/SimpleMailer/commands/MailerCommand.php commands/

- Run 'crontab -e' and add the Mailer command:

    0,30 * * * * /path/to/your/application/protected/yiic mailer #Send emails in queue every 30 minutes

- In your protected/config/main.php and protected/config.console.php add the following lines:

	'import' => array(
	    ...
		'application.modules.SimpleMailer.components.*',
		'application.modules.SimpleMailer.models.*',
		...
	),
	'modules' => array(
	    ...
		'SimpleMailer' => array(
		    'attachImages' => true, // This is the default value, for attaching the images used into the emails.
		    'sendEmailLimit'=> 500, // Also the default value, how much emails should be sent when calling yiic mailer
		),
		...
	),
- Now access SimpleMailer via http://<your_project_ip_or_domain>/SimpleMailer/. You're done.


Usage:
======

The workflow of SimpleMailer can be resumed in these 10 simple steps:

1.- Create an email template: You can create it using any WYSIWYG HTML tool like Kompozer. Also you can use the
 MailChimp templates freely available at their site.
2.- Preview it in your HTML web browser. If it looks good for you then source it (by pressing Ctrl+U in your browser
 window). Select it (Ctrl+A) and copy it (Ctrl+C).
3.- Go to http://<your_app_ip_or_domain>/SimpleMailer/ and click on 'Create SimpleMailerTemplate'.
4.- Fill in the form. The 'Name' field is important since you're gonna access this template by it.
5.- Select the 'Body' field and paste what you previously copied (Ctrl+V).
6.- Fill in the 'Alternative body' field with a text-only version of your email.
7.- Click the 'Save' button.
8.- Go to your desired controller action and write down the following lines:

    $template_vars = array(
        //Put any variables you need to replace. the suggested format is '__KEY__' => 'value'. More about this below.
        '__username__' => 'John Doe',
        '__quote__' => 'Roses are red, Violets are blue. Sugar is sweet, Who the hell are you?',
    );
    //If you want to enqueue the email for later sending just call SimpleMailer::enqueue() instead. Same params, please.
    //For list email sending read below.
    SimpleMailer::send('johndoe@example.com', 'template_name', $template_vars);

9.- Now access your action from your browser. After execution John Doe will receive a personalized email with a quote
 for him.
10.- Congratulate yourself. You made it. :)


About templates:
================
There's no specific rules to set your template variables in your templates except that the characters used must be in
the [a-zA-Z0-9_] range (that means numbers, letters, and underscore).

I use the '__variable__' format (two underscores, the variable name in lowercase and two underscores more), however you
can use any other format you like if it meets the range requirement (010101VARIABLE010101 will do it but it is
unreadable. You got the point).

Whatever format you choose to use, keep in mind that you need to pass your '__variables__' => 'value' array to the
SimpleMailer::send() or SimpleMailer::enqueue() function.

The module also allows you to add dynamic generated content to the template. This is done using another array with the
same format considerations explained above, called 'Template Partials'. Let's explain this with an example. Suppose
Clark Kent and Lois Lane are subscribed to the daily offers that appear on the Daily Planet (you're their web developer,
of course). Then your code should look like this:

    //This should return Lois and Clark.
    $users = User::model()->findByAttributes(array(
        'subscribed' => '1',
    ));

    $template_partials = array(
        '__todaysOffers__' => Offer::buildPartialForOffersMail(); //You already programmed this function. It spits HTML.
    );

    foreach ($users as $user)  {
        //The user info for this example is stored in a 'Profile' object.
        $template_vars = array(
            '__username__' => $user->profile->firstName . ' ' . $user->profile->lastName;
        );
        //You can also enqueue emails
        SimpleMailer::enqueue($user->profile->email, 'template_offers', $template_vars, $template_partials);
    }

Of course, there's a more practical way to achieve this. And now, enter the 'Lists'.


That Kind-of-Mailing-List thing:
================================
Well, it's not a full-fledged mailing list management system. It's more like a "send this email to all these folks"
that get the users emails using a SQL sentence. For the simplicity of this document I'll refer to this as a 'List'.

Creating a new List:
--------------------

Follow these steps:

- Connect to your SQL server and test the SQL sentence you want to use. An example could be:

    SELECT email FROM profile WHERE location='atlantis';

- If it worked as expected, copy it.
- Go to http://<your_app_ip_or_domain>/SimpleMailer/ and click on 'Create SimpleMailerList'.
- Fill in the form. The 'Name' field is important since you're gonna access this List by it. Don't forget to paste your
  SQL sentence (you can omit the semicolon at the end of the sentence). Please, fetch just the email column from the
  database. Didn't test what happens if I try to fetch two or more columns. If you wanna experiment with it go ahead.
- Go to your desired controller action and write down the following lines:

    //SimpleMailer::sendToList() enqueues all the messages being sent.
    SimpleMailer::sendToList('list_name', 'template_name', $template_partials);

- Now access your action from your browser. After execution your emails will be enqueued and will eventually being sent.

A word about mails for Lists:
-----------------------------
As you can see you can't use template variables when sending to a List. This is a limitation this module has. Currently
I have no idea how to get each user's info and substitute it into a template being used for a list to make a more
personalized message. I you have any ideas about how to do this please drop me some lines.


FAQ:
====

Q: I followed all the steps to install this module but I can't send email. Why?
A: Be sure you (or the System Administrator of the company you work for) configured properly a MTA in your server
   (lets say Postfix). This needs a little knowledge of system administration but as usual you can google the steps
   to do such configuration. This also will improve the response time of your PHP scripts when sending email.

   You won't be able to send emails until you configure your MTA. Period.

Q: What's the difference between a template variable and a template partial?
A: A template partial is a piece of HTML code that is going to be substituted ONCE, when compiling a template. It's a
   dynamic text that you want to change every time you send an email to a bunch of people without modifying the entire
   template. I.E. the today's offers that changes every day and you want to keep all members of your list up to date.
   It's the message you want to communicate.

   Template variables are related to the user who is receiving the email. This variables are used for personalizing the
   email with specific information of the user.

Q: How can I reset the sm_queue table?
A: I didn't execute these SQL commands yet, however they should work (uncle Google told me):

       DELETE FROM sm_queue;
       ALTER TABLE sm_queue AUTO_INCREMENT=1

   This will erase all your queued emails. You've been warned.

Q: I think I've found a bug. What should I do?
A: Fix it, make a patch and send it to me. :) Or at least report it in the SimpleMailer extension page. I will try
   to fix it as soon as I can. The same thing goes to suggestions or improvements.

Q: Why is your English so funny?
A: I'm not a native English speaker. I do speak some English, but my native language is Spanish. And if you think
   my English is funny you have to hear me trying to utter words in German or Russian. You will laugh your arse off.
   For sure.

Q: Do you have Twitter/Facebook/Email?
A: Yes I do.

Q: I have another question regarding this extension that is not in this FAQ. How can I contact you?
A: You can leave me a message here or at the Yii Forum SimpleMailer page.

