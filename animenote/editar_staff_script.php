<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$animeid=$_GET['idanime'];
if($_SESSION["tipo"]==1){
$sqlget = "SELECT * FROM `staff` WHERE `id_animes` LIKE '$animeid'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: anime_informacao?idanime='.$animeid.''));
while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
	$id_pessoa[] = $row['id_pessoa'];
}
for($i=0; $i<count($id_pessoa); $i++){
	if(isset($_POST['staff'.$id_pessoa[$i].''])){
		$sqlget = "SELECT * FROM `staff` WHERE `id_animes` LIKE '$animeid' AND `id_pessoa` like '".$id_pessoa[$i]."'";
		$sqldata = mysqli_query($con,$sqlget) or die (header('Location: novo_anime'));
		$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
		$id_staff = $row['id_staff'];
		$delete="DELETE FROM `staff_cargo` WHERE `id_staff` like $id_staff";
		mysqli_query($con, $delete) or die (header('Location:anime_informacao?idanime='.$animeid.''));
		foreach($_POST['staff'.$id_pessoa[$i].''] as $checkbox){
			$insere="INSERT INTO `staff_cargo` (`id_staff`, `id_cargo`) VALUES ('$id_staff', '$checkbox');";
			mysqli_query($con, $insere) or die (header('Location:anime_informacao?idanime='.$animeid.''));
		}
	}
}
}
header('Location:anime_informacao?idanime='.$animeid.'');
?>