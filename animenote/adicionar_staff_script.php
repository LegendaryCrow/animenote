<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$animeid=$_GET['idanime'];
if($_SESSION["tipo"]==1){
$insere="INSERT INTO `staff` (`id_staff`, `id_animes`, `id_pessoa`) VALUES (NULL, '$animeid', '".$_POST['pessoa']."');";
mysqli_query($con, $insere) or die (header('Location:novo_anime'));

$sqlget = "SELECT * FROM `staff` WHERE `id_animes` LIKE '$animeid' AND `id_pessoa` like '".$_POST['pessoa']."'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: novo_anime'));
$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
$id_staff = $row['id_staff'];

if(isset($_POST['cargos'])){
foreach($_POST['cargos'] as $checkbox){
	echo $insere="INSERT INTO `staff_cargo` (`id_staff`, `id_cargo`) VALUES ('$id_staff', '$checkbox');";
	mysqli_query($con, $insere) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
}
}
header('Location:anime_informacao?idanime='.$animeid.'');
?>