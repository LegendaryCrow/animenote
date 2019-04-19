<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
if(isset($_POST['nome'])){
	if($_POST['nome']!=""){
		$update="UPDATE configs SET nome='".$_POST['nome']."' WHERE configs_id like 1";
		mysqli_query($con, $update) or die (header('Location:opcoes_admin'));
	}
}
if(isset($_POST['slogan'])){
	if($_POST['slogan']!=""){
		$update="UPDATE configs SET slogan='".$_POST['slogan']."' WHERE configs_id like 1";
		mysqli_query($con, $update) or die (header('Location:opcoes_admin'));
	}
}
if(isset($_POST['copyright'])){
	if($_POST['copyright']!=""){
		$update="UPDATE configs SET copyright='".$_POST['copyright']."' WHERE configs_id like 1";
		mysqli_query($con, $update) or die (header('Location:opcoes_admin'));
	}
}
if(isset($_POST['procura'])){
	if($_POST['procura']!=""){
		$update="UPDATE configs SET procurar_quantidade='".$_POST['procura']."' WHERE configs_id like 1";
		mysqli_query($con, $update) or die (header('Location:opcoes_admin'));
	}
}
if(isset($_POST['manutencao'])){
	if($_POST['manutencao']!=""){
		$update="UPDATE configs SET data_manutencao='".$_POST['manutencao']."' WHERE configs_id like 1";
		mysqli_query($con, $update) or die (header('Location:opcoes_admin'));
	}
}
if(!empty($_FILES["myimage"]["name"])){ 
	$imagename=$_FILES["myimage"]["name"];
	$imagetmp=addslashes (file_get_contents($_FILES['myimage']['tmp_name']));
	$insert_image="UPDATE `configs` SET `favicon` = '$imagetmp' WHERE configs_id like 1";
	mysqli_query($con, $insert_image) or die (header('Location:opcoes_admin'));
}
header('Location:opcoes_admin');
?>