<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$idpersonagem=$_GET['idpersonagem'];
if($_SESSION["tipo"]==1){
$sqlget = "SELECT * FROM `ator_voz` WHERE `id_personagens` LIKE '$idpersonagem'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: personagem_informacao?idpersonagem='.$idpersonagem.''));
while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
	echo $id_pessoa[] = $row['id_pessoa'];
}
for($i=0; $i<count($id_pessoa); $i++){
	if(isset($_POST['lingua'.$id_pessoa[$i].''])){
		echo $delete="DELETE FROM `ator_voz` WHERE `id_personagens` like $idpersonagem and `id_pessoa` like $id_pessoa[$i]";
		mysqli_query($con, $delete) or die (header('Location: personagem_informacao?idpersonagem='.$idpersonagem.''));
		
		echo $insere="INSERT INTO `ator_voz` (`id_ator_voz`, `id_personagens`, `id_pessoa`, `id_linguas`) VALUES (NULL, '$idpersonagem', '$id_pessoa[$i]', '".$_POST['lingua'.$id_pessoa[$i].'']."');";
		mysqli_query($con, $insere) or die (header('Location: personagem_informacao?idpersonagem='.$idpersonagem.''));
	}
}
}
header('Location: personagem_informacao?idpersonagem='.$idpersonagem.'');
?>