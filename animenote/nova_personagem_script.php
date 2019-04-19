<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$imagename=$_FILES["myimage"]["name"];
$imagetmp=addslashes (file_get_contents($_FILES['myimage']['tmp_name']));

if(empty($_POST['biografia'])){
	$_POST['biografia']="";
}

$insere="INSERT INTO `personagens` (`id_personagens`, `nome`, `biografia`, `imagem`) VALUES (NULL, '".$_POST['nome']."', '".$_POST['biografia']."', '$imagetmp');";
mysqli_query($con, $insere) or die (header('Location:nova_personagem'));

$sqlget = "SELECT * FROM personagens where nome like '".$_POST['nome']."' and biografia like '".$_POST['biografia']."'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: nova_pessoa'));
$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
$idpersonagem = $row['id_personagens'];
header('Location:personagem_informacao?idpersonagem='.$idpersonagem.'');
?>