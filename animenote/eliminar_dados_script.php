<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
if(isset($_SESSION["tipo"])){if($_SESSION["tipo"]==1){
if(isset($_GET['dado'])){
	$dado=$_GET['dado'];
	if($dado=="1"){
		$delete="DELETE FROM `tipo` WHERE id_tipo like '".$_POST['pessoa']."'";
		mysqli_query($con, $delete) or die (header('Location:opcoes_admin'));
	}
	if($dado=="2"){
		$delete="DELETE FROM `temporada` WHERE id_temporada like '".$_POST['pessoa']."'";
		mysqli_query($con, $delete) or die (header('Location:opcoes_admin'));
	}
	if($dado=="3"){
		$delete="DELETE FROM `estudio` WHERE id_estudio like '".$_POST['pessoa']."'";
		mysqli_query($con, $delete) or die (header('Location:opcoes_admin'));
	}
	if($dado=="4"){
		$delete="DELETE FROM `generos` WHERE id_generos like '".$_POST['pessoa']."'";
		mysqli_query($con, $delete) or die (header('Location:opcoes_admin'));
	}
	if($dado=="5"){
		$delete="DELETE FROM `licenciadores` WHERE id_licenciadores like '".$_POST['pessoa']."'";
		mysqli_query($con, $delete) or die (header('Location:opcoes_admin'));
	}
	if($dado=="6"){
		$delete="DELETE FROM `produtores` WHERE id_produtores like '".$_POST['pessoa']."'";
		mysqli_query($con, $delete) or die (header('Location:opcoes_admin'));
	}
	if($dado=="7"){
		$delete="DELETE FROM `linguas` WHERE id_linguas like '".$_POST['pessoa']."'";
		mysqli_query($con, $delete) or die (header('Location:opcoes_admin'));
	}
	if($dado=="8"){
		$delete="DELETE FROM `fonte` WHERE id_fonte like '".$_POST['pessoa']."'";
		mysqli_query($con, $delete) or die (header('Location:opcoes_admin'));
	}
	if($dado=="9"){
		$delete="DELETE FROM `classificacao` WHERE id_classificacao like '".$_POST['pessoa']."'";
		mysqli_query($con, $delete) or die (header('Location:opcoes_admin'));
	}
	if($dado=="10"){
		$delete="DELETE FROM `cargo` WHERE id_cargo like '".$_POST['pessoa']."'";
		mysqli_query($con, $delete) or die (header('Location:opcoes_admin'));
	}
}}}
header('Location:opcoes_admin');
?>