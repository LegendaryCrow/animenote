<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
if(isset($_SESSION["id_utilizador"])){
$id_utilizador=$_SESSION["id_utilizador"];
$id_mensagem=$_GET['mensagem'];
//Retirar informação da tabela utilizador
echo $sqlget = "SELECT * FROM mensagens where id_mensagens like $id_mensagem and id_utilizador2 like '$id_utilizador' and (checked like '0' or checked like '1')";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: mensagens'));
$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
echo $checked=$row['checked'];
if($checked==0){
	$inserir="UPDATE `mensagens` SET `checked` = '1' WHERE `mensagens`.`id_mensagens` = $id_mensagem;";
	mysqli_query($con, $inserir) or die (header('Location: mensagens'));
	header('Location: mensagens');
	exit();
}
if($checked==1){
	echo $inserir="UPDATE `mensagens` SET `checked` = '0' WHERE `mensagens`.`id_mensagens` = $id_mensagem;";
	mysqli_query($con, $inserir) or die (header('Location: mensagens'));
	header('Location: mensagens');
	exit();
}
header('Location: mensagens');
exit();
}else{
	header('Location: mensagens');
}
?>