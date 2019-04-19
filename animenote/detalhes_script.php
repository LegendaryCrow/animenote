<?php
session_start();
if (isset($_POST['editar_informacao'])) {
	header('Content-Type: text/html; charset=UTF-8');
	$con=new mysqli ("localhost","root","","animenote");
	mysqli_set_charset($con, 'utf8mb4');
	$update="UPDATE utilizador SET genero='". $_POST["genero"]."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
	mysqli_query($con, $update) or die (header('Location:perfil'));
	$update="UPDATE utilizador SET data_nascimento='". $_POST["data"]."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
	mysqli_query($con, $update) or die (header('Location:perfil'));
	$update="UPDATE utilizador SET localizacao='". $_POST["localizacao"]."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
	mysqli_query($con, $update) or die (header('Location:perfil'));	
	header('Location:editar_perfil');
}elseif(empty($_SESSION["id_utilizador"])){
	header('Location:login');
}else{
	header('Location:editar_perfil');
}
?>