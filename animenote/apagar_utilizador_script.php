<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$idutilizador=$_GET['idutilizador'];
if($_SESSION["tipo"]==1){
	if($idutilizador!=$_SESSION["id_utilizador"]){
		echo $delete="DELETE FROM `utilizador` WHERE `id_utilizador` like $idutilizador";
		mysqli_query($con, $delete) or die (header('Location: perfil'));
	}
}
header('Location: procurar');
?>