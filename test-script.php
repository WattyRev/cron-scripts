<?php 
function sendMail($to, $subject, $message, $from, $format) {
	$header = "From: $from";
	if(isset($format) && $format === 'html') {
		$header .= "\r\nMIME-Version: 1.0\r\n";
		$header .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
	}
 
	if (mail($to, $subject, $message, $header)) {
		return true;
	} else {
		return false;
	}
}
mail('wattyrev@gmail.com', 'test cron job', 'this is a test', 'noreply@wattydev.com', 'text');