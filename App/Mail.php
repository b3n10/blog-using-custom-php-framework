<?php

namespace App;

use Mailgun\Mailgun;
use \App\Config;

class Mail {

	public static function send($to, $subject, $text, $html) {
		# First, instantiate the SDK with your API credentials
		$api_key = getenv('MAILGUN_API_KEY');
		$domain = getenv('MAILGUN_DOMAIN');

		$mg = Mailgun::create($api_key);

		# Now, compose and send your message.
		# $mg->messages()->send($domain, $params);
		$mg->messages()->send($domain, [
			'from'   	=> 'postmaster@sandbox6643293a77004904b2a8d67ffd82105e.mailgun.org',
			'to'      => $to,
			'subject' => $subject,
			'text'    => $text,
			'html'    => $html
		]);
	}

}
