<?php
$ticket =$_POST["ticket"];
$id_user=$_POST["iduser"];
$idcity=$_POST["idtown"];
$content =$_POST["content"];
$image1 = $_POST["image0"];
$image2 = $_POST["image1"];
$image3 = $_POST["image2"];
$image4 = $_POST["image3"];
$image5 = $_POST["image4"];
$idcat = $_POST["idcat"];
 
$postvars = array('ticket' => $ticket, 'id_user' => $id_user, 'id' => $idcity, 'content' => $content,'cat_id'=>$idcat,'img1'=> $image1,'img2'=> $image2,'img3'=> $image3,'img4'=> $image4,'img5'=> $image5);
 
require_once(dirname(__FILE__) . "/config.php");
$url = 'wpapi/post/';
init_curl($url,$postvars);
?>
