<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$idpersonagem=$_GET['idpersonagem'];
if($_SESSION["tipo"]==1){
$insere="INSERT INTO `ator_voz` (`id_ator_voz`, `id_personagens`, `id_pessoa`, `id_linguas`) VALUES (NULL, '$idpersonagem', '".$_POST['pessoaid']."', '".$_POST['linguaid']."');";
mysqli_query($con, $insere) or die (header('Location:personagem_informacao?idpersonagem='.$idpersonagem.''));
}
header('Location:personagem_informacao?idpersonagem='.$idpersonagem.'');
?>