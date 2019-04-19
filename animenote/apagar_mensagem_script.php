<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
if(isset($_SESSION["id_utilizador"])){
$id_utilizador=$_SESSION["id_utilizador"];
$id_mensagem=$_GET['mensagem'];
//Retirar informação da tabela utilizador
$sqlget = "SELECT * FROM mensagens where id_mensagens like $id_mensagem and id_utilizador2 like $id_utilizador and (checked like '0' or checked like '1')";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: login'));
$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
if(isset($row['id_mensagens'])){
	$inserir="DELETE FROM `mensagens` WHERE id_mensagens like ".$row['id_mensagens']."";
	mysqli_query($con, $inserir) or die (header('Location: mensagens'));
	header('Location: mensagens');
	exit();
}else{
$sqlget = "SELECT * FROM mensagens where id_mensagens like $id_mensagem and id_utilizador1 like $id_utilizador and checked like '2'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: login'));
$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
if(isset($row['id_mensagens'])){
	$inserir="DELETE FROM `mensagens` WHERE id_mensagens like ".$row['id_mensagens']."";
	mysqli_query($con, $inserir) or die (header('Location: mensagens'));
	header('Location: mensagens');
	exit();
}else{
header('Location:mensagens');
exit();
}
}
}else{
	header('Location:mensagens');
}
?>