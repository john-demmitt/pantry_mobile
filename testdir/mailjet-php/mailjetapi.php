<?php

include( "php-mailjet-v3-simple.class.php" );

$apiKey = '6b7d4000b0a4e514117843f700f4c4b6';
$secretKey = 'f611a621b675332384ebcc42899ed329';

$mj = new Mailjet ( $apiKey, $secretKey );

$params = array(
   "method" => "POST",
   "from" => "wvfoodpantry@westvalleyfoodpantry.org",
   "to" => "suzukawa@hotmail.com",
   "subject" => "Maybe we are making progress",
   "text" => "Now we see if removing the domain from the safe list works."
   );

$result = $mj->sendEmail($params);

if ($mj->_response_code == 200)
  echo "success - email sent";
else
  echo "error - ".$mj->_response_code;
