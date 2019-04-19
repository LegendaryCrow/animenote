<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$personagemid=$_GET['idpersonagem'];
if($_SESSION["tipo"]==1){
	echo $delete="DELETE FROM `personagens` WHERE `id_personagens` like $personagemid";
	mysqli_query($con, $delete) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
}
header('Location: procurar');
?>