<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$idpersonagem=$_GET['idpersonagem'];
$idpessoa=$_GET['idpessoa'];
if($_SESSION["tipo"]==1){
	$delete="DELETE FROM `ator_voz` WHERE id_personagens like $idpersonagem and id_pessoa like $idpessoa";
	mysqli_query($con, $delete) or die (header('Location:personagem_informacao?idpersonagem='.$idpersonagem.''));
}
header('Location:personagem_informacao?idpersonagem='.$idpersonagem.'');
?>