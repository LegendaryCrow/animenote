<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$pessoaid=$_GET['idpessoa'];
if($_SESSION["tipo"]==1){
	echo $delete="DELETE FROM `pessoa` WHERE `id_pessoa` like $pessoaid";
	mysqli_query($con, $delete) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
}
header('Location: procurar');
?>