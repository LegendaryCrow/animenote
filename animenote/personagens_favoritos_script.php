<?php
session_start();
if(isset($_SESSION["id_utilizador"])){
$personagemid=$_GET['idpersonagem'];
$favorito=0;
if(isset($personagemid)){
	header('Content-Type: text/html; charset=UTF-8');
    $con=new mysqli ("localhost","root","","animenote");
    mysqli_set_charset($con, 'utf8mb4');
	//Retirar informação da tabela personagens_favoritas
	$sqlget = "SELECT * FROM personagens_favoritas where id_utilizador='" . $_SESSION["id_utilizador"] . "'";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	
	if(isset($row['id_personagem1'])){
		if($row['id_personagem1']==$personagemid){
			$favorito=1;
			$favoritos="UPDATE personagens_favoritas SET id_personagem1=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			if(isset($row['id_personagem2'])){
				$favoritos="UPDATE personagens_favoritas SET id_personagem1='".$row['id_personagem2']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
				$favoritos="UPDATE personagens_favoritas SET id_personagem2=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			}
			if(isset($row['id_personagem3'])){
				$favoritos="UPDATE personagens_favoritas SET id_personagem2='".$row['id_personagem3']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
				$favoritos="UPDATE personagens_favoritas SET id_personagem3=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			}
			if(isset($row['id_personagem4'])){
				$favoritos="UPDATE personagens_favoritas SET id_personagem3='".$row['id_personagem4']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
				$favoritos="UPDATE personagens_favoritas SET id_personagem4=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			}
			if(isset($row['id_personagem5'])){
				$favoritos="UPDATE personagens_favoritas SET id_personagem4='".$row['id_personagem5']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
				$favoritos="UPDATE personagens_favoritas SET id_personagem5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			}
		}
	}
    if(isset($row['id_personagem2'])){
		if($row['id_personagem2']==$personagemid){
			$favorito=1;
			$favoritos="UPDATE personagens_favoritas SET id_personagem2=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			if(isset($row['id_personagem3'])){
				$favoritos="UPDATE personagens_favoritas SET id_personagem2='".$row['id_personagem3']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
				$favoritos="UPDATE personagens_favoritas SET id_personagem3=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			}
			if(isset($row['id_personagem4'])){
				$favoritos="UPDATE personagens_favoritas SET id_personagem3='".$row['id_personagem4']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
				$favoritos="UPDATE personagens_favoritas SET id_personagem4=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			}
			if(isset($row['id_personagem5'])){
				$favoritos="UPDATE personagens_favoritas SET id_personagem4='".$row['id_personagem5']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
				$favoritos="UPDATE personagens_favoritas SET id_personagem5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			}
		}
	}
    if(isset($row['id_personagem3'])){
		if($row['id_personagem3']==$personagemid){
			$favorito=1;
			$favoritos="UPDATE personagens_favoritas SET id_personagem3=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			if(isset($row['id_personagem4'])){
				$favoritos="UPDATE personagens_favoritas SET id_personagem3='".$row['id_personagem4']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
				$favoritos="UPDATE personagens_favoritas SET id_personagem4=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			}
			if(isset($row['id_personagem5'])){
				$favoritos="UPDATE personagens_favoritas SET id_personagem4='".$row['id_personagem5']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
				$favoritos="UPDATE personagens_favoritas SET id_personagem5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			}
		}
	}
	if(isset($row['id_personagem4'])){
		if($row['id_personagem4']==$personagemid){
			$favorito=1;
			$favoritos="UPDATE personagens_favoritas SET id_personagem4=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			if(isset($row['id_personagem5'])){
				$favoritos="UPDATE personagens_favoritas SET id_personagem4='".$row['id_personagem5']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
				$favoritos="UPDATE personagens_favoritas SET id_personagem5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			}
		}
	}
	if(isset($row['id_personagem5'])){
		if($row['id_personagem5']==$personagemid){
			$favorito=1;
			$favoritos="UPDATE personagens_favoritas SET id_personagem5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
		}
	}
	if($favorito==0){
	if(isset($row['id_personagem1'])){
    	if(isset($row['id_personagem2'])){
    		if(isset($row['id_personagem3'])){
    			if(isset($row['id_personagem4'])){
    				if(isset($row['id_personagem5'])){
    					header("Location:editar_perfil?favoritos=erro");
					}else{
						$favoritos="UPDATE personagens_favoritas SET id_personagem5='$personagemid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
						mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
						header('Location:personagem_informacao?idpersonagem='.$personagemid.'');
					}
				}else{
					$favoritos="UPDATE personagens_favoritas SET id_personagem4='$personagemid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
					mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
					header('Location:personagem_informacao?idpersonagem='.$personagemid.'');
				}
			}else{
				$favoritos="UPDATE personagens_favoritas SET id_personagem3='$personagemid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
				header('Location:personagem_informacao?idpersonagem='.$personagemid.'');
			}
		}else{
			$favoritos="UPDATE personagens_favoritas SET id_personagem2='$personagemid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
			header('Location:personagem_informacao?idpersonagem='.$personagemid.'');
		}
	}else{
		$favoritos="UPDATE personagens_favoritas SET id_personagem1='$personagemid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
		mysqli_query($con, $favoritos) or die (header('Location:personagem_informacao?idpersonagem='.$personagemid.''));
		header('Location:personagem_informacao?idpersonagem='.$personagemid.'');
	}
	}else{
		header('Location:personagem_informacao?idpersonagem='.$personagemid.'');
	}
}else{
	header("Location:procurar");
}}else{header("Location:login");}
?>