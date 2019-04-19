<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');
$animeid=$_GET['idanime'];
if(isset($_POST['titulo'])){
	if($_POST['titulo']!=""){
		$update="UPDATE animes SET nome='".$_POST['titulo']."' WHERE id_animes like $animeid";
		mysqli_query($con, $update) or die (header('Location:anime_informacao?idanime='.$animeid.''));
	}
}
if(isset($_POST['sinopse'])){
	if($_POST['sinopse']!=""){
		$update="UPDATE animes SET sinopse='".$_POST['sinopse']."' WHERE id_animes like $animeid";
		mysqli_query($con, $update) or die (header('Location:anime_informacao?idanime='.$animeid.''));
	}
}
if(isset($_POST['lancamento'])){
	if($_POST['lancamento']!=""){
		$update="UPDATE animes SET lancamento='".$_POST['lancamento']."' WHERE id_animes like $animeid";
		mysqli_query($con, $update) or die (header('Location:anime_informacao?idanime='.$animeid.''));
	}
}
if(isset($_POST['duracao'])){
	if($_POST['duracao']!=""){
		$update="UPDATE animes SET duracao='".$_POST['duracao']."' WHERE id_animes like $animeid";
		mysqli_query($con, $update) or die (header('Location:anime_informacao?idanime='.$animeid.''));
	}
}
if(isset($_POST['episodios_anime'])){
	if($_POST['episodios_anime']!=""){
		$update="UPDATE animes SET episodios='".$_POST['episodios_anime']."' WHERE id_animes like $animeid";
		mysqli_query($con, $update) or die (header('Location:anime_informacao?idanime='.$animeid.''));
		$update="UPDATE lista_animes SET episodios_vistos='".$_POST['episodios_anime']."' WHERE id_animes like $animeid and episodios_vistos>'".$_POST['episodios_anime']."'";
		mysqli_query($con, $update) or die (header('Location:anime_informacao?idanime='.$animeid.''));
	}
}
if(isset($_POST['fonte'])){
	if($_POST['fonte']!=""){
		$update="UPDATE animes SET id_fonte='".$_POST['fonte']."' WHERE id_animes like $animeid";
		mysqli_query($con, $update) or die (header('Location:anime_informacao?idanime='.$animeid.''));
	}
}
if(isset($_POST['tipo'])){
	if($_POST['tipo']!=""){
		$update="UPDATE animes SET id_tipo='".$_POST['tipo']."' WHERE id_animes like $animeid";
		mysqli_query($con, $update) or die (header('Location:anime_informacao?idanime='.$animeid.''));
	}
}
if(isset($_POST['estado_editar'])){
	if($_POST['estado_editar']!=""){
		$update="UPDATE animes SET id_estado='".$_POST['estado_editar']."' WHERE id_animes like $animeid";
		mysqli_query($con, $update) or die (header('Location:anime_informacao?idanime='.$animeid.''));
	}
}
if(isset($_POST['temporada'])){
	if($_POST['temporada']!=""){
		$update="UPDATE animes SET id_temporada='".$_POST['temporada']."' WHERE id_animes like $animeid";
		mysqli_query($con, $update) or die (header('Location:anime_informacao?idanime='.$animeid.''));
	}
}
if(isset($_POST['classificacao'])){
	if($_POST['classificacao']!=""){
		$update="UPDATE animes SET id_classificacao='".$_POST['classificacao']."' WHERE id_animes like $animeid";
		mysqli_query($con, $update) or die (header('Location:anime_informacao?idanime='.$animeid.''));
	}
}
if(!empty($_FILES["myimage"]["name"])){ 
	$imagename=$_FILES["myimage"]["name"];
	$imagetmp=addslashes (file_get_contents($_FILES['myimage']['tmp_name']));
	$insert_image="UPDATE `animes` SET `imagem` = '$imagetmp' WHERE id_animes like $animeid";
	mysqli_query($con, $insert_image) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
$delete="DELETE FROM `animes_produtores` WHERE id_animes like $animeid";
mysqli_query($con, $delete) or die (header('Location:'.$page.'?idanime='.$animeid.''));
if(isset($_POST['produtores'])){
foreach($_POST['produtores'] as $checkbox){
	$insere="INSERT INTO `animes_produtores` (`id_animes`, `id_produtores`) VALUES ('$animeid', '$checkbox')";
	mysqli_query($con, $insere) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
}
$delete="DELETE FROM `animes_licenciadores` WHERE id_animes like $animeid";
mysqli_query($con, $delete) or die (header('Location:'.$page.'?idanime='.$animeid.''));
if(isset($_POST['licenciadores'])){
foreach($_POST['licenciadores'] as $checkbox){
	$insere="INSERT INTO `animes_licenciadores` (`id_animes`, `id_licenciadores`) VALUES ('$animeid', '$checkbox')";
	mysqli_query($con, $insere) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
}
$delete="DELETE FROM `animes_estudio` WHERE id_animes like $animeid";
mysqli_query($con, $delete) or die (header('Location:'.$page.'?idanime='.$animeid.''));
if(isset($_POST['estudio'])){
foreach($_POST['estudio'] as $checkbox){
	$insere="INSERT INTO `animes_estudio` (`id_animes`, `id_estudio`) VALUES ('$animeid', '$checkbox')";
	mysqli_query($con, $insere) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
}
$delete="DELETE FROM `animes_generos` WHERE id_animes like $animeid";
mysqli_query($con, $delete) or die (header('Location:'.$page.'?idanime='.$animeid.''));
if(isset($_POST['generos'])){
foreach($_POST['generos'] as $checkbox){
	$insere="INSERT INTO `animes_generos` (`id_animes`, `id_generos`) VALUES ('$animeid', '$checkbox')";
	mysqli_query($con, $insere) or die (header('Location:anime_informacao?idanime='.$animeid.''));
}
}
header('Location:anime_informacao?idanime='.$animeid.'');
?>