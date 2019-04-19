<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$personagemid=$_GET['idpersonagem'];
if(isset($_POST['titulo'])){
	if($_POST['titulo']!=""){
		$update="UPDATE personagens SET nome='".$_POST['titulo']."' WHERE id_personagens like $personagemid";
		mysqli_query($con, $update) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
	}
}
if(isset($_POST['biografia'])){
	if($_POST['biografia']!=""){
		$biografia=$_POST['biografia'];
		$update="UPDATE personagens SET biografia='".$biografia."' WHERE id_personagens like $personagemid";
		mysqli_query($con, $update) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
	}
}
if(!empty($_FILES["myimage"]["name"])){ 
	$imagename=$_FILES["myimage"]["name"];
	$imagetmp=addslashes (file_get_contents($_FILES['myimage']['tmp_name']));
	$insert_image="UPDATE `personagens` SET `imagem` = '$imagetmp' WHERE id_personagens like $personagemid";
	mysqli_query($con, $insert_image) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
}
header('Location:personagem_informacao?idpersonagem='.$personagemid.'');
?>