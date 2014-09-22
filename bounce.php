#!/usr/bin/php
<?php
//BSD License -- USE AT YOUR OWN RISK. WILL DEFINITELY CAUSE DATA LOSS
// Amazon SES bounce process for CiviCRM using IMAP. Redirect your bounce messages to an e-mail address and then use this to process that.
/***
Copyright (c) 2014, Sami Khan
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
***/
//cat bounce.txt | grep emailAddress | cut -f2 -d '>' | sort | uniq > output.txt
/* connect to imap */
DEFINE('DRUPAL_PATH', '/path/to/drupal')
//imapinfo
$hostname = '{hostXXX.hostmonster.com:993/imap/ssl}INBOX';
$username = '';
$password = '';


require_once DRUPAL_PATH.'/sites/all/modules/civicrm/api/api.php';
require_once DRUPAL_PATH.'/sites/default/civicrm.settings.php';
date_default_timezone_set('MST');

function mail_set_hold($email){
  $params = array(
    'email' => $email,
    'version' => 3,
  );
  $result = civicrm_api( 'Email','get',$params );

  foreach( $result['values'] as $key => $values){
    $values['version'] = 3;
    $values['on_hold'] = 1;
    $result = civicrm_api('Email', 'create', $values);
    file_put_contents('blocked.txt', $email."\n",  FILE_APPEND);

  }
}


/* try to connect */
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to IMAP: ' . imap_last_error());

/* grab emails */
$emails = imap_search($inbox, "UNSEEN");


/* if emails are returned, cycle through each... */
if($emails) {
file_put_contents('blocked.txt', date('F j, Y, g:i a')."Bounces found, processing... \n",  FILE_APPEND);	
	/* for every email... */
	foreach($emails as $email_number) {
		$body = imap_fetchbody($inbox,$email_number,1);
		$body = explode( '--', $body);
		$body = json_decode(trim($body[0]));
        	if(isset($body->notificationType)){
	  		foreach($body->bounce->bouncedRecipients as $email){
				//print_r($email->emailAddress);
				mail_set_hold( trim($email->emailAddress) );
				imap_delete ( $inbox, $email_number);
			}
        	}else{
                }

	
	}
} 

/* close the connection */
imap_close($inbox);

