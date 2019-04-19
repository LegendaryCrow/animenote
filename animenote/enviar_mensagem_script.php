<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
if(isset($_SESSION["id_utilizador"])){
$id_utilizador=$_SESSION["id_utilizador"];
$mensagem_para=$_GET['utilizador'];
$assunto=$_POST['assunto'];
$mensagem=$_POST['mensagem'];
$data = date('Y-m-d H:i:s');
//Retirar informação da tabela utilizador
$sqlget = "SELECT * FROM utilizador where nome='" . $mensagem_para . "' and ativacao like '0'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: login'));
$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
$id_para = $row['id_utilizador'];
if($id_para!=$id_utilizador){
	$inserir="INSERT INTO `mensagens` (`id_mensagens`, `id_utilizador1`, `id_utilizador2`, `assunto`, `mensagem`, `data_hora`, `checked`) VALUES(NULL, '$id_utilizador', '$id_para', '$assunto', '$mensagem', '$data', '0');";
	mysqli_query($con, $inserir) or die (header('Location:perfil?utilizador='.$mensagem_para.''));
	$inserir="INSERT INTO `mensagens` (`id_mensagens`, `id_utilizador1`, `id_utilizador2`, `assunto`, `mensagem`, `data_hora`, `checked`) VALUES(NULL, '$id_utilizador', '$id_para', '$assunto', '$mensagem', '$data', '2');";
	mysqli_query($con, $inserir) or die (header('Location:perfil?utilizador='.$mensagem_para.''));
	header('Location:perfil?utilizador='.$mensagem_para.'');
	exit();
}
header('Location:perfil');
exit();
}else{
	header('Location:perfil');
}
?>