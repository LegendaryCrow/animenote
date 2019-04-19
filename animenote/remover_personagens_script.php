<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$animeid=$_GET['idanime'];
$personagensid=$_GET['idpersonagens'];
if($_SESSION["tipo"]==1){
	$delete="DELETE FROM `animes_personagens` WHERE id_animes like $animeid and id_personagens like $personagensid";
	mysqli_query($con, $delete) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
header('Location:anime_informacao?idanime='.$animeid.'');
?>