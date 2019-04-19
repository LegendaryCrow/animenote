<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$imagename=$_FILES["myimage"]["name"];
$imagetmp=addslashes (file_get_contents($_FILES['myimage']['tmp_name']));

if(empty($_POST['website'])){
	$_POST['website']="";
}if(empty($_POST['outros'])){
	$_POST['outros']="";
}if(empty($_POST['aniversario'])){
	$_POST['aniversario']="";
}if(empty($_POST['sobrenome'])){
	$_POST['sobrenome']="";
}if(empty($_POST['nome_proprio'])){
	$_POST['nome_proprio']="";
}

$insere="INSERT INTO `pessoa` (`id_pessoa`, `nome`, `nome_proprio`, `sobrenome`, `aniversario`, `website`, `outros`, `imagem`) VALUES (NULL, '".$_POST['titulo']."', '".$_POST['nome_proprio']."', '".$_POST['sobrenome']."', '".$_POST['aniversario']."', '".$_POST['website']."', '".$_POST['outros']."', '$imagetmp');";
mysqli_query($con, $insere) or die (header('Location:novo_anime'));

$sqlget = "SELECT * FROM pessoa where nome like '".$_POST['titulo']."' and nome_proprio like '".$_POST['nome_proprio']."'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: nova_pessoa'));
$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
$idpessoa = $row['id_pessoa'];
header('Location:pessoa_informacao?idpessoa='.$idpessoa.'');
?>