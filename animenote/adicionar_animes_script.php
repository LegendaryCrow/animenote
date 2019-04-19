<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$animeid=$_GET['idanime'];
$total_ep=$_GET['totalep'];
$popup=$_GET['popup'];
if($popup=="true"){
	$page="editar_animes";
}else{
	$page="anime_informacao";
}
if($animeid==''){
	header('Location: procurar');
	exit();
}else if($_POST["episodios"]>$total_ep || $total_ep==''){
	header('Location:'.$page.'?idanime='.$animeid.'');
	exit();
}
$data = date('Y-m-d H:i:s');
//Se existir sessao iniciada vai buscar informacao do anime/utilizador
if(isset($_SESSION["nome"]) && isset($animeid)){
	//Retirar informação da tabela lista_animes
	$sqlget = "SELECT * FROM `lista_animes` WHERE `id_utilizador`='" . $_SESSION["id_utilizador"] . "' and id_animes like $animeid";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    $add_idestado = $row['id_estado'] ;
	if($_POST["episodios"]==""){
		$_POST["episodios"]="0";
	}
	if (isset($_POST['adicionar'])) {
		if(isset($add_idestado)){
			//Retirar nota antiga
			$sqlget = "SELECT nota FROM `lista_animes` WHERE id_utilizador like '".$_SESSION["id_utilizador"]."' and id_animes like $animeid";
    		$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
			$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
			$nota_antiga = $row['nota'] ;
			//Atualizar lista_animes
			$update="UPDATE lista_animes SET id_estado='". $_POST["estado"]."' WHERE `id_utilizador`='" . $_SESSION["id_utilizador"] . "' and id_animes like $animeid";
			mysqli_query($con, $update) or die (header('Location:'.$page.'?idanime='.$animeid.''));
			$update="UPDATE lista_animes SET episodios_vistos='". $_POST["episodios"]."' WHERE `id_utilizador`='" . $_SESSION["id_utilizador"] . "' and id_animes like $animeid";
			mysqli_query($con, $update) or die (header('Location:'.$page.'?idanime='.$animeid.''));
			$update="UPDATE lista_animes SET nota='". $_POST["nota"]."' WHERE `id_utilizador`='" . $_SESSION["id_utilizador"] . "' and id_animes like $animeid";
			mysqli_query($con, $update) or die (header('Location:'.$page.'?idanime='.$animeid.''));
			//Atualizar nota média
			$sqlget = "SELECT nota_media, total_utilizadores FROM `animes` WHERE `id_animes` like $animeid";
    		$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
			$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
			$nota_media = $row['nota_media'] ;
			$total_utilizadores = $row['total_utilizadores'] ;
			//Nova média
			$nova_nota_media=(($nota_media*$total_utilizadores)-$nota_antiga+$_POST["nota"])/$total_utilizadores;
			//Atualizar nota média
			$update="UPDATE animes SET nota_media='$nova_nota_media' WHERE id_animes like $animeid";
			mysqli_query($con, $update) or die (header('Location:'.$page.'?idanime='.$animeid.''));
		}else{
			//Inserir novo valor na lista_animes
			$insere="INSERT INTO `lista_animes` (`id_utilizador`, `id_animes`, `id_estado`, `episodios_vistos`, `nota`) VALUES ('" . $_SESSION["id_utilizador"] . "', '$animeid', '". $_POST["estado"]."', '". $_POST["episodios"]."', '". $_POST["nota"]."');";
			mysqli_query($con, $insere) or die (header('Location:'.$page.'?idanime='.$animeid.''));
			//Atualizar nota média
			$sqlget = "SELECT nota_media, total_utilizadores FROM `animes` WHERE `id_animes` like $animeid";
    		$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
			$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
			$nota_media = $row['nota_media'] ;
			$total_utilizadores = $row['total_utilizadores'] ;
			//Nova média
			$novo_total_utilizadores=$total_utilizadores+1;
			$nova_nota_media=(($nota_media*$total_utilizadores)+$_POST["nota"])/$novo_total_utilizadores;
			//Atualizar nota média
			$update="UPDATE animes SET nota_media='$nova_nota_media', total_utilizadores='$novo_total_utilizadores' WHERE id_animes like $animeid";
			mysqli_query($con, $update) or die (header('Location:'.$page.'?idanime='.$animeid.''));
		}
		//Atualizar historico_anime
		$delete = "DELETE FROM `historico_anime` WHERE `id_utilizador` like " . $_SESSION["id_utilizador"] . " and id_anime like $animeid";
		mysqli_query($con, $delete) or die (header('Location:'.$page.'?idanime='.$animeid.''));
		$insere="INSERT INTO `historico_anime` (`id_anime`, `id_utilizador`, `data`) VALUES ('$animeid', '" . $_SESSION["id_utilizador"] . "', '$data');";
		mysqli_query($con, $insere) or die (header('Location:'.$page.'?idanime='.$animeid.''));
		//Terminar
		header('Location:'.$page.'?idanime='.$animeid.'');
		exit();
	}elseif (isset($_POST['apagar'])) {
		//Retirar nota antiga
		$sqlget = "SELECT nota FROM `lista_animes` WHERE id_utilizador like '".$_SESSION["id_utilizador"]."' and id_animes like $animeid";
    	$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
		$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
		$nota_antiga = $row['nota'] ;
		//Apagar o valor da lista_anime
		$delete="DELETE FROM `lista_animes` WHERE `id_utilizador` like '" . $_SESSION["id_utilizador"] . "' and `id_animes` like $animeid";
		mysqli_query($con, $delete) or die (header('Location:'.$page.'?idanime='.$animeid.''));
		//Atualizar nota média
		$sqlget = "SELECT nota_media, total_utilizadores FROM `animes` WHERE `id_animes` like $animeid";
    	$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
		$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
		$nota_media = $row['nota_media'] ;
		$total_utilizadores = $row['total_utilizadores'] ;
		//Nova média
		$novo_total_utilizadores=$total_utilizadores-1;
		$nova_nota_media=(($nota_media*$total_utilizadores)-$nota_antiga)/$novo_total_utilizadores;
		//Atualizar nota média
		$update="UPDATE animes SET nota_media='$nova_nota_media', total_utilizadores='$novo_total_utilizadores' WHERE 	id_animes like $animeid";
		mysqli_query($con, $update) or die (header('Location:'.$page.'?idanime='.$animeid.''));
		header('Location:'.$page.'?idanime='.$animeid.'');
		exit();
	}
}else{
	header('Location:'.$page.'?idanime='.$animeid.'');
	exit();
}
?>