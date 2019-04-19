<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$imagename=$_FILES["myimage"]["name"];
$imagetmp=addslashes (file_get_contents($_FILES['myimage']['tmp_name']));
$insere="INSERT INTO `animes` (`id_animes`, `nome`, `episodios`, `lancamento`, `duracao`, `sinopse`, `imagem`, `id_fonte`, `id_tipo`, `id_estado`, `id_temporada`, `id_classificacao`, `nota_media`, `total_utilizadores`, `top_anime`) VALUES (NULL, '".$_POST['titulo']."', '".$_POST['episodios_anime']."', '".$_POST['lancamento']."', '".$_POST['duracao']."', '".$_POST['sinopse']."', '$imagetmp', '".$_POST['fonte']."', '".$_POST['tipo']."', '".$_POST['estado_editar']."', '".$_POST['temporada']."', '".$_POST['classificacao']."', '0', '0', NULL);";
mysqli_query($con, $insere) or die (header('Location:novo_anime'));

$sqlget = "SELECT * FROM animes where nome like '".$_POST['titulo']."' and episodios like '".$_POST['episodios_anime']."'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: novo_anime'));
$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
echo $animeid = $row['id_animes'];

if(isset($_POST['produtores'])){
foreach($_POST['produtores'] as $checkbox){
	echo $insere="INSERT INTO `animes_produtores` (`id_animes`, `id_produtores`) VALUES ('$animeid', '$checkbox')";
	mysqli_query($con, $insere) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
}

if(isset($_POST['licenciadores'])){
foreach($_POST['licenciadores'] as $checkbox){
	$insere="INSERT INTO `animes_licenciadores` (`id_animes`, `id_licenciadores`) VALUES ('$animeid', '$checkbox')";
	mysqli_query($con, $insere) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
}

if(isset($_POST['estudio'])){
foreach($_POST['estudio'] as $checkbox){
	$insere="INSERT INTO `animes_estudio` (`id_animes`, `id_estudio`) VALUES ('$animeid', '$checkbox')";
	mysqli_query($con, $insere) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
}

if(isset($_POST['generos'])){
foreach($_POST['generos'] as $checkbox){
	$insere="INSERT INTO `animes_generos` (`id_animes`, `id_generos`) VALUES ('$animeid', '$checkbox')";
	mysqli_query($con, $insere) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
}
header('Location:anime_informacao?idanime='.$animeid.'');
?>