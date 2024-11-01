<?php
 $http_auth_ident = ''; // username:password 
 $timeout = 10; 
 $server = "http://www.tipao.com/";
 
 function init_curl($url,$postvars){
 global $server;
 $ch = curl_init($server.$url);
 curl_setopt($ch, CURLOPT_FRESH_CONNECT, true); 
 curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
 if (preg_match('#^https://#i', $url))
 {
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
 } 
 curl_setopt($ch, CURLOPT_NOBODY, TRUE);
 curl_setopt($ch, CURLOPT_POST ,1);
 curl_setopt($ch, CURLOPT_POSTFIELDS ,$postvars);
 //curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1); 
 curl_setopt($ch, CURLOPT_HEADER      ,0);  // DO NOT RETURN HTTP HEADERS 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
 if(!empty($http_auth_ident)){
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); // Définition de la méthode d'authentification du serveur
	curl_setopt($ch, CURLOPT_USERPWD, $http_auth_ident); // Définition des identifiants
 }
 
 $ret = curl_exec($ch);
 if (!$ret) {
    echo curl_error($ch);
} else {
	echo $ret;
}
 curl_close($ch);
}
 ?>