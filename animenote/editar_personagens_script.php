<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$animeid=$_GET['idanime'];
if($_SESSION["tipo"]==1){
$sqlget = "SELECT * FROM `animes_personagens` WHERE `id_animes` LIKE '$animeid'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: anime_informacao?idanime='.$animeid.''));
while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
	$id_personagens[] = $row['id_personagens'];
}
for($i=0; $i<count($id_personagens); $i++){
	if(isset($_POST['tipopersonagem'.$id_personagens[$i].''])){
		$delete="DELETE FROM `animes_personagens` WHERE `id_animes` like $animeid and `id_personagens` like $id_personagens[$i]";
		mysqli_query($con, $delete) or die (header('Location:anime_informacao?idanime='.$animeid.''));
		
		$insere="INSERT INTO `animes_personagens` (`id_animes`, `id_personagens`, `tipo`) VALUES ('$animeid', '$id_personagens[$i]', '".$_POST['tipopersonagem'.$id_personagens[$i].'']."');";
		mysqli_query($con, $insere) or die (header('Location:anime_informacao?idanime='.$animeid.''));
	}
}
}
header('Location:anime_informacao?idanime='.$animeid.'');
?>