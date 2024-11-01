<?php

 $ville =$_POST["ville"];
 $lang = "FR";

 
 $postvars = array('city' => $ville,'country'=> $lang);
 
 $ch = curl_init('http://tipao.com/wpapi/search_city/format/json');
 curl_setopt($ch, CURLOPT_POST ,1);
 curl_setopt($ch, CURLOPT_POSTFIELDS ,$postvars);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1); 
 curl_setopt($ch, CURLOPT_HEADER      ,0);  // DO NOT RETURN HTTP HEADERS 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
 $ret = curl_exec($ch);
 if (!$ret) {
    echo curl_error($ch);
} else {
	echo $ret;
}
 curl_close($ch);

 
exit;
?>
