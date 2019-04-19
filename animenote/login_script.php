<?php
session_start();
if(count($_POST)>0) {
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');

$sqlget = "SELECT * FROM utilizador WHERE nome='" . $_POST["utilizador"] . "' and password = '". md5($_POST["password"])."' and ativacao='0'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
if(is_array($row)) {
	$_SESSION["id_utilizador"] = $row[id_utilizador];
	$_SESSION["nome"] = $row[nome];
	$_SESSION["password"] = $row[password];
	$_SESSION["tipo"] = $row[tipo];
}else{
	header("Location:login?erro=true");
}
}
if(isset($_SESSION["id_utilizador"])) {
	header("Location: perfil");
}
?>