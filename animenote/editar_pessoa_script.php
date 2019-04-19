<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$pessoaid=$_GET['idpessoa'];
if(isset($_POST['titulo'])){
	if($_POST['titulo']!=""){
		$update="UPDATE pessoa SET nome='".$_POST['titulo']."' WHERE id_pessoa like $pessoaid";
		mysqli_query($con, $update) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
	}
}
if(isset($_POST['nome_proprio'])){
	if($_POST['nome_proprio']!=""){
		$update="UPDATE pessoa SET nome_proprio='".$_POST['nome_proprio']."' WHERE id_pessoa like $pessoaid";
		mysqli_query($con, $update) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
	}
}
if(isset($_POST['sobrenome'])){
	if($_POST['sobrenome']!=""){
		$update="UPDATE pessoa SET sobrenome='".$_POST['sobrenome']."' WHERE id_pessoa like $pessoaid";
		mysqli_query($con, $update) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
	}
}
if(isset($_POST['aniversario'])){
	if($_POST['aniversario']!=""){
		$update="UPDATE pessoa SET aniversario='".$_POST['aniversario']."' WHERE id_pessoa like $pessoaid";
		mysqli_query($con, $update) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
	}
}
if(isset($_POST['website'])){
	if($_POST['website']!=""){
		$update="UPDATE pessoa SET website='".$_POST['website']."' WHERE id_pessoa like $pessoaid";
		mysqli_query($con, $update) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
	}
}
if(isset($_POST['outros'])){
	if($_POST['outros']!=""){
		$update="UPDATE pessoa SET outros='".$_POST['outros']."' WHERE id_pessoa like $pessoaid";
		mysqli_query($con, $update) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
	}
}
if(!empty($_FILES["myimage"]["name"])){ 
	$imagename=$_FILES["myimage"]["name"];
	$imagetmp=addslashes (file_get_contents($_FILES['myimage']['tmp_name']));
	$insert_image="UPDATE `pessoa` SET `imagem` = '$imagetmp' WHERE id_pessoa like $pessoaid";
	mysqli_query($con, $insert_image) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
}
header('Location:pessoa_informacao?idpessoa='.$pessoaid.'');
?>