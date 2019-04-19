<?php
session_start();
if(isset($_SESSION["id_utilizador"])){
$pessoaid=$_GET['idpessoa'];
$favorito=0;
if(isset($pessoaid)){
	header('Content-Type: text/html; charset=UTF-8');
    $con=new mysqli ("localhost","root","","animenote");
    mysqli_set_charset($con, 'utf8mb4');
	//Retirar informação da tabela pessoas_favoritas
	$sqlget = "SELECT * FROM pessoas_favoritas where id_utilizador='" . $_SESSION["id_utilizador"] . "'";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	
	if(isset($row['id_pessoa1'])){
		if($row['id_pessoa1']==$pessoaid){
			$favorito=1;
			$favoritos="UPDATE pessoas_favoritas SET id_pessoa1=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			if(isset($row['id_pessoa2'])){
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa1='".$row['id_pessoa2']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa2=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			}
			if(isset($row['id_pessoa3'])){
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa2='".$row['id_pessoa3']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa3=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			}
			if(isset($row['id_pessoa4'])){
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa3='".$row['id_pessoa4']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa4=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			}
			if(isset($row['id_pessoa5'])){
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa4='".$row['id_pessoa5']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			}
		}
	}
    if(isset($row['id_pessoa2'])){
		if($row['id_pessoa2']==$pessoaid){
			$favorito=1;
			$favoritos="UPDATE pessoas_favoritas SET id_pessoa2=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			if(isset($row['id_pessoa3'])){
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa2='".$row['id_pessoa3']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa3=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			}
			if(isset($row['id_pessoa4'])){
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa3='".$row['id_pessoa4']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa4=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			}
			if(isset($row['id_pessoa5'])){
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa4='".$row['id_pessoa5']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			}
		}
	}
    if(isset($row['id_pessoa3'])){
		if($row['id_pessoa3']==$pessoaid){
			$favorito=1;
			$favoritos="UPDATE pessoas_favoritas SET id_pessoa3=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			if(isset($row['id_pessoa4'])){
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa3='".$row['id_pessoa4']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa4=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			}
			if(isset($row['id_pessoa5'])){
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa4='".$row['id_pessoa5']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			}
		}
	}
	if(isset($row['id_pessoa4'])){
		if($row['id_pessoa4']==$pessoaid){
			$favorito=1;
			$favoritos="UPDATE pessoas_favoritas SET id_pessoa4=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			if(isset($row['id_pessoa5'])){
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa4='".$row['id_pessoa5']."' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			}
		}
	}
	if(isset($row['id_pessoa5'])){
		if($row['id_pessoa5']==$pessoaid){
			$favorito=1;
			$favoritos="UPDATE pessoas_favoritas SET id_pessoa5=NULL where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
		}
	}
	if($favorito==0){
	if(isset($row['id_pessoa1'])){
    	if(isset($row['id_pessoa2'])){
    		if(isset($row['id_pessoa3'])){
    			if(isset($row['id_pessoa4'])){
    				if(isset($row['id_pessoa5'])){
    					header("Location:editar_perfil?favoritos=erro");
					}else{
						$favoritos="UPDATE pessoas_favoritas SET id_pessoa5='$pessoaid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
						mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
						header('Location:pessoa_informacao?idpessoa='.$pessoaid.'');
					}
				}else{
					$favoritos="UPDATE pessoas_favoritas SET id_pessoa4='$pessoaid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
					mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
					header('Location:pessoa_informacao?idpessoa='.$pessoaid.'');
				}
			}else{
				$favoritos="UPDATE pessoas_favoritas SET id_pessoa3='$pessoaid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
				mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
				header('Location:pessoa_informacao?idpessoa='.$pessoaid.'');
			}
		}else{
			$favoritos="UPDATE pessoas_favoritas SET id_pessoa2='$pessoaid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
			mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
			header('Location:pessoa_informacao?idpessoa='.$pessoaid.'');
		}
	}else{
		$favoritos="UPDATE pessoas_favoritas SET id_pessoa1='$pessoaid' where id_utilizador='". $_SESSION["id_utilizador"]."'";
		mysqli_query($con, $favoritos) or die (header('Location:pessoa_informacao?idpessoa='.$pessoaid.''));
		header('Location:pessoa_informacao?idpessoa='.$pessoaid.'');
	}
	}else{
		header('Location:pessoa_informacao?idpessoa='.$pessoaid.'');
	}
}else{
	header("Location:procurar");
}}else{header("Location:login");}
?>