<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
if(isset($_SESSION["tipo"])){if($_SESSION["tipo"]==1){
if(isset($_GET['dado'])){
	$dado=$_GET['dado'];
	if($dado=="1"){
		$insere="INSERT INTO `tipo` (`id_tipo`, `nome`) VALUES (NULL, '".$_POST['nome']."');";
		mysqli_query($con, $insere) or die (header('Location:opcoes_admin'));
	}
	if($dado=="2"){
		$insere="INSERT INTO `temporada` (`id_temporada`, `nome`) VALUES (NULL, '".$_POST['nome']."');";
		mysqli_query($con, $insere) or die (header('Location:opcoes_admin'));
	}
	if($dado=="3"){
		$insere="INSERT INTO `estudio` (`id_estudio`, `nome`) VALUES (NULL, '".$_POST['nome']."');";
		mysqli_query($con, $insere) or die (header('Location:opcoes_admin'));
	}
	if($dado=="4"){
		$insere="INSERT INTO `generos` (`id_generos`, `nome`, `descricao`) VALUES (NULL, '".$_POST['nome']."', '".$_POST['descricao']."');";
		mysqli_query($con, $insere) or die (header('Location:opcoes_admin'));
	}
	if($dado=="5"){
		$insere="INSERT INTO `licenciadores` (`id_licenciadores`, `nome`) VALUES (NULL, '".$_POST['nome']."');";
		mysqli_query($con, $insere) or die (header('Location:opcoes_admin'));
	}
	if($dado=="6"){
		$insere="INSERT INTO `produtores` (`id_produtores`, `nome`) VALUES (NULL, '".$_POST['nome']."');";
		mysqli_query($con, $insere) or die (header('Location:opcoes_admin'));
	}
	if($dado=="7"){
		$insere="INSERT INTO `linguas` (`id_linguas`, `lingua`) VALUES (NULL, '".$_POST['nome']."');";
		mysqli_query($con, $insere) or die (header('Location:opcoes_admin'));
	}
	if($dado=="8"){
		$insere="INSERT INTO `fonte` (`id_fonte`, `nome`) VALUES (NULL, '".$_POST['nome']."');";
		mysqli_query($con, $insere) or die (header('Location:opcoes_admin'));
	}
	if($dado=="9"){
		$insere="INSERT INTO `classificacao` (`id_classificacao`, `nome`) VALUES (NULL, '".$_POST['nome']."');";
		mysqli_query($con, $insere) or die (header('Location:opcoes_admin'));
	}
	if($dado=="10"){
		$insere="INSERT INTO `cargo` (`id_cargo`, `nome`) VALUES (NULL, '".$_POST['nome']."');";
		mysqli_query($con, $insere) or die (header('Location:opcoes_admin'));
	}
}}}
header('Location:opcoes_admin');
?>