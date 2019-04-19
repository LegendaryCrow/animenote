<?php
session_start();
$connection = mysqli_connect('localhost', 'root', '', 'animenote');
$update_detalhes="UPDATE `utilizador` SET `genero` = NULL where id_utilizador='" . $_SESSION["id_utilizador"] . "'";
mysqli_query($connection, $remove_image) or die (header('Location: procurar'));
header('Location: editar_perfil');
?>