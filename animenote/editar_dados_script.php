<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');

if(isset($_POST['email'])){
	if($_POST['email']!=""){
		$update="UPDATE `utilizador` SET `email` = '".$_POST['email']."' WHERE `utilizador`.`id_utilizador` = ".$_SESSION["id_utilizador"].";";
		mysqli_query($con, $update) or die (header('Location:editar_perfil'));
	}
}
if(isset($_POST['nome'])){
	if($_POST['nome']!=""){
		$update="UPDATE `utilizador` SET `nome` = '".$_POST['nome']."' WHERE `utilizador`.`id_utilizador` = ".$_SESSION["id_utilizador"].";";
		mysqli_query($con, $update) or die (header('Location:editar_perfil'));
	}
}
if(isset($_POST['password']) && isset($_POST['password1'])){
	if($_POST['password']!="" && $_POST['password']==$_POST['password1']){
		$update="UPDATE `utilizador` SET `password` = '".$_POST['password']."' WHERE `utilizador`.`id_utilizador` = ".$_SESSION["id_utilizador"].";";
		mysqli_query($con, $update) or die (header('Location:editar_perfil'));
	}
}
header('Location:editar_perfil');
?>