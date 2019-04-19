<?php
session_start();
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');

$sqlget = "SELECT `id_animes` FROM `animes`";
$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
	$sqlget = "SELECT(((select total_utilizadores from animes WHERE `id_animes` like ".$row['id_animes'].")*100/(select count(total_utilizadores) from animes))*(select nota_media from animes WHERE `id_animes` like ".$row['id_animes'].")/10) as nota";
    $sqldata1 = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row1 = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC);
	echo $update="UPDATE animes SET top_anime = '".$row1['nota']."' where id_animes = ".$row['id_animes']."";
	mysqli_query($con, $update) or die (header('Location:index'));
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>