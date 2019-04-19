<?php
session_start();
if(isset($_SESSION["id_utilizador"])){
$animeid=$_GET['idanime'];
$favorito=0;
if(isset($animeid)){
	header('Content-Type: text/html; charset=UTF-8');
    $con=new mysqli ("localhost","root","","animenote");
    mysqli_set_charset($con, 'utf8mb4');
	//Retirar informação da tabela animes_favoritos
	$sqlget = "SELECT * FROM animes_favoritos where id_utilizador='" . $_SESSION["id_utilizador"] . "'";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	
	if(isset($row['id_animes1'])){
		if($row['id_animes1']==$animeid){
			$favorito=1;
			$favoritos="UPDATE animes_favoritos SET id_animes1=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			if(isset($row['id_animes2'])){
				$favoritos="UPDATE animes_favoritos SET id_animes1='".$row['id_animes2']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
				$favoritos="UPDATE animes_favoritos SET id_animes2=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			}
			if(isset($row['id_animes3'])){
				$favoritos="UPDATE animes_favoritos SET id_animes2='".$row['id_animes3']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
				$favoritos="UPDATE animes_favoritos SET id_animes3=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			}
			if(isset($row['id_animes4'])){
				$favoritos="UPDATE animes_favoritos SET id_animes3='".$row['id_animes4']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
				$favoritos="UPDATE animes_favoritos SET id_animes4=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			}
			if(isset($row['id_animes5'])){
				$favoritos="UPDATE animes_favoritos SET id_animes4='".$row['id_animes5']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
				$favoritos="UPDATE animes_favoritos SET id_animes5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			}
		}
	}
    if(isset($row['id_animes2'])){
		if($row['id_animes2']==$animeid){
			$favorito=1;
			$favoritos="UPDATE animes_favoritos SET id_animes2=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			if(isset($row['id_animes3'])){
				$favoritos="UPDATE animes_favoritos SET id_animes2='".$row['id_animes3']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
				$favoritos="UPDATE animes_favoritos SET id_animes3=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			}
			if(isset($row['id_animes4'])){
				$favoritos="UPDATE animes_favoritos SET id_animes3='".$row['id_animes4']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
				$favoritos="UPDATE animes_favoritos SET id_animes4=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			}
			if(isset($row['id_animes5'])){
				$favoritos="UPDATE animes_favoritos SET id_animes4='".$row['id_animes5']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
				$favoritos="UPDATE animes_favoritos SET id_animes5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			}
		}
	}
    if(isset($row['id_animes3'])){
		if($row['id_animes3']==$animeid){
			$favorito=1;
			$favoritos="UPDATE animes_favoritos SET id_animes3=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			if(isset($row['id_animes4'])){
				$favoritos="UPDATE animes_favoritos SET id_animes3='".$row['id_animes4']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
				$favoritos="UPDATE animes_favoritos SET id_animes4=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			}
			if(isset($row['id_animes5'])){
				$favoritos="UPDATE animes_favoritos SET id_animes4='".$row['id_animes5']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
				$favoritos="UPDATE animes_favoritos SET id_animes5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			}
		}
	}
	if(isset($row['id_animes4'])){
		if($row['id_animes4']==$animeid){
			$favorito=1;
			$favoritos="UPDATE animes_favoritos SET id_animes4=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			if(isset($row['id_animes5'])){
				$favoritos="UPDATE animes_favoritos SET id_animes4='".$row['id_animes5']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
				$favoritos="UPDATE animes_favoritos SET id_animes5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			}
		}
	}
	if(isset($row['id_animes5'])){
		if($row['id_animes5']==$animeid){
			$favorito=1;
			$favoritos="UPDATE animes_favoritos SET id_animes5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
		}
	}
	if($favorito==0){
	if(isset($row['id_animes1'])){
    	if(isset($row['id_animes2'])){
    		if(isset($row['id_animes3'])){
    			if(isset($row['id_animes4'])){
    				if(isset($row['id_animes5'])){
    					header("Location:editar_perfil?favoritos=erro");
					}else{
						$favoritos="UPDATE animes_favoritos SET id_animes5='$animeid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
						mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
						header('Location:anime_informacao?idanime='.$animeid.'');
					}
				}else{
					$favoritos="UPDATE animes_favoritos SET id_animes4='$animeid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
					mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
					header('Location:anime_informacao?idanime='.$animeid.'');
				}
			}else{
				$favoritos="UPDATE animes_favoritos SET id_animes3='$animeid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
				header('Location:anime_informacao?idanime='.$animeid.'');
			}
		}else{
			$favoritos="UPDATE animes_favoritos SET id_animes2='$animeid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
			header('Location:anime_informacao?idanime='.$animeid.'');
		}
	}else{
		$favoritos="UPDATE animes_favoritos SET id_animes1='$animeid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
		mysqli_query($con, $favoritos) or die (header('Location:anime_informacao?idanime='.$animeid.''));
		header('Location:anime_informacao?idanime='.$animeid.'');
	}
	}else{
		header('Location:anime_informacao?idanime='.$animeid.'');
	}
}else{
	header("Location:procurar");
}}else{header("Location:login");}
?>