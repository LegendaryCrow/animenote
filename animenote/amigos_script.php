<?php
session_start();
if(isset($_SESSION["id_utilizador"])){
$id_utilizador=$_SESSION["id_utilizador"];
$amigo=$_GET['utilizador'];
$aceitar=$_GET['aceitar'];
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
//Retirar informação da tabela utilizador
$sqlget = "SELECT * FROM utilizador where nome='" . $amigo . "' and ativacao like '0'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: login'));
$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
$id_amigo = $row['id_utilizador'];
if($aceitar==2){
	$delete="DELETE FROM `lista_amigos` WHERE id_utilizador1='$id_utilizador' and id_utilizador2='$id_amigo'";
	mysqli_query($con, $delete) or die (header('Location:perfil?utilizador='.$id_amigo.''));
	header('Location:perfil?utilizador='.$amigo.'');
	$delete="DELETE FROM `lista_amigos` WHERE id_utilizador1='$id_amigo' and id_utilizador2='$id_utilizador'";
	mysqli_query($con, $delete) or die (header('Location:perfil?utilizador='.$amigo.''));
	header('Location:perfil?utilizador='.$amigo.'');
	exit();
}else{
if($id_amigo!=$id_utilizador){
	//Retirar informação da tabela animes_favoritos
	$sqlget = "SELECT * FROM lista_amigos where id_utilizador1='$id_utilizador' and id_utilizador2='$id_amigo'";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	if(isset($row['id_lista_amigos'])){
		$delete="DELETE FROM `lista_amigos` WHERE id_utilizador1='$id_utilizador' and id_utilizador2='$id_amigo'";
		mysqli_query($con, $delete) or die (header('Location:perfil?utilizador='.$id_amigo.''));
		header('Location:perfil?utilizador='.$amigo.'');
		$delete="DELETE FROM `lista_amigos` WHERE id_utilizador1='$id_amigo' and id_utilizador2='$id_utilizador'";
		mysqli_query($con, $delete) or die (header('Location:perfil?utilizador='.$amigo.''));
		header('Location:perfil?utilizador='.$amigo.'');
		exit();
	}else{
		$inserir="INSERT INTO `lista_amigos` (`id_lista_amigos`, `id_utilizador1`, `id_utilizador2`, `confirmacao`) VALUES (NULL, '$id_utilizador', '$id_amigo', 1)";
		mysqli_query($con, $inserir) or die (header('Location:perfil?utilizador='.$amigo.''));	
		if($aceitar==1){
			$inserir="UPDATE `lista_amigos` SET `confirmacao` = '0' WHERE id_utilizador1='$id_utilizador' and id_utilizador2='$id_amigo'";
			mysqli_query($con, $inserir) or die (header('Location:perfil?utilizador='.$amigo.''));	
			$inserir="UPDATE `lista_amigos` SET `confirmacao` = '0' WHERE id_utilizador1='$id_amigo' and id_utilizador2='$id_utilizador'";
			mysqli_query($con, $inserir) or die (header('Location:perfil?utilizador='.$amigo.''));	
		}
		header('Location:perfil?utilizador='.$amigo.'');
		exit();
	}
}else{
	header('Location:perfil?utilizador='.$amigo.'');
}
}
}
?>