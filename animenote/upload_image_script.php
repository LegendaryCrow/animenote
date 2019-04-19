<?php
session_start();
$connection = mysqli_connect('localhost', 'root', '', 'animenote');

$imagename=$_FILES["myimage"]["name"]; 

//Get the content of the image and then add slashes to it 
$imagetmp=addslashes (file_get_contents($_FILES['myimage']['tmp_name']));

//Insert the image name and image content in image_table
$insert_image="UPDATE `utilizador` SET `imagem` = '$imagetmp' where id_utilizador='" . $_SESSION["id_utilizador"] . "'";
mysqli_query($connection, $insert_image) or die (header('Location: procurar'));
header('Location: editar_perfil');
?>