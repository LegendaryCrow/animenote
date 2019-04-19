<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$animeid=$_GET['idanime'];
$staffid=$_GET['idstaff'];
if($_SESSION["tipo"]==1){
	$delete="DELETE FROM `staff` WHERE id_animes like $animeid and id_pessoa like $staffid";
	mysqli_query($con, $delete) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}

header('Location:anime_informacao?idanime='.$animeid.'');
?>