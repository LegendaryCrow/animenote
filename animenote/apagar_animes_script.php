<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$animeid=$_GET['idanime'];
if($_SESSION["tipo"]==1){
	echo $delete="DELETE FROM `animes` WHERE `id_animes` like $animeid";
	mysqli_query($con, $delete) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
header('Location: procurar');
?>