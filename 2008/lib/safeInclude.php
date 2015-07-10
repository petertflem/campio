<?php

function containsParentDir($path) {
	return preg_match("#\.\.#",$path);
}

function checkPageLegal($page) {
//echo "$page<br>";
	if (!preg_match("#\.\.\/#",$page)  //does not contain ../
		&& preg_match("#^[a-z0-9_./]+$#i",$page)  //contains only characters legal for files
	  	&& file_exists($page)
		) {
		return true;
	} else {
	  print "Invalid page requested. The attempt has been logged.";
	  # Her kan du putte en rutine som logger forsøket på å gå rundt systemet ditt!
	  //reportHackingAttempt($page);
	  return false;
	}
}
function reportHackingAttempt($page) {
	$agent = $_SERVER['HTTP_USER_AGENT']; 
	$ip = $_SERVER['REMOTE_ADDR']; 
	$ref = $_SERVER['HTTP_REFERER'];
	$host = $_SERVER['HTTP_HOST'];

	$msg = "Forsøk på å include filen: $page\n";
	$msg .= "\n\n\nBrowser: $agent \nIP: $ip \nHost: $host \nReferrer: $ref"; 				
			
	mail("ronny@tonsberg-fallskjermklubb.com", "Forsøk på hacking av tonsberg-fallskjermklubb.com", $msg);
}
?>