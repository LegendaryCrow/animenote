<?php
session_start();
unset($_SESSION["id_utilizador"]);
unset($_SESSION["nome"]);
unset($_SESSION["password"]);
unset($_SESSION["tipo"]);
header("Location:login");
?>