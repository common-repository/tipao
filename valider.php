<?php 
// Données à envoyer
$login = $_GET["login"];
$pass = hash('sha256', $_GET["password"]);
$postvars = array('login' => $login, 'pass' => $pass);


require_once(dirname(__FILE__) . "/config.php");
$url = 'wpapi/connect/format/json';
init_curl($url,$postvars);
?>