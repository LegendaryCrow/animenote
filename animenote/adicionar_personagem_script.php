<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$animeid=$_GET['idanime'];
if($_SESSION["tipo"]==1){
echo $insere="INSERT INTO `animes_personagens` (`id_animes`, `id_personagens`, `tipo`) VALUES ('$animeid', '".$_POST['personagemid']."', '".$_POST['tipopersonagem']."');";
mysqli_query($con, $insere) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
header('Location:anime_informacao?idanime='.$animeid.'');
?>