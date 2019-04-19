<!DOCTYPE HTML>
<html>
	<head>
	<?php
	session_start();
    header('Content-Type: text/html; charset=UTF-8');
    $con=new mysqli ("localhost","root","","animenote");
    mysqli_set_charset($con, 'utf8mb4');
		$animeid=$_GET['idanime'];
	if($animeid==''){
		header('Location: lista_Animes');
	}
	//Retirar informação da tabela configs
	$sqlget = "SELECT * from configs where configs_id like 1 ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$nome_website = $row['nome'];
	$slogan_website = $row['slogan'];
	$copyright_website = $row['copyright'];
	$procurar_quantidade = $row['procurar_quantidade'];
	$favicon = base64_encode( $row['favicon'] ) ;
	$data_manutencao = $row['data_manutencao'];
	//Manutencao
	$data = date('Y-m-d');
	if($data_manutencao==$data){
		$update="UPDATE configs SET data_manutencao=DATE_ADD(data_manutencao,INTERVAL 7 DAY)";
		mysqli_query($con, $update) or die (header('Location:index'));
		header('Location: manutencao');
		exit();
	}
	//Retirar informação da tabela animes
    $sqlget = "SELECT * FROM animes where id_animes like $animeid";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: lista_Animes'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$id_animes = $row['id_animes'];
    $nome = $row['nome'];
	$episodios = $row['episodios'] ;
    $lancamento = $row['lancamento'] ;
	$duracao = $row['duracao'] ;
	$sinopse = $row['sinopse'] ;
	$imagem = base64_encode( $row['imagem'] ) ;
	$id_classificacao = $row['id_classificacao'];
	$id_tipo = $row['id_tipo'];
	$id_estado = $row['id_estado'];
	$id_temporada = $row['id_temporada'];
	$id_fonte = $row['id_fonte'];
	///Se existir sessao iniciada vai buscar informacao do anime/utilizador
	if(isset($_SESSION["nome"])){
		//Retirar informação da tabela lista_animes
		$sqlget = "SELECT * FROM `lista_animes` WHERE `id_utilizador`='" . $_SESSION["id_utilizador"] . "' and `id_animes`='" . $_GET["idanime"] . "'";
    	$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
		$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    	$add_idestado = $row['id_estado'] ;
    	$add_episodiosvistos = $row['episodios_vistos'] ;
    	$add_nota = $row['nota'] ;
	}
    ?>
		<title>Editar <?php echo $nome;?> - <?php echo $nome_website; ?></title>
		<link rel="shortcut icon" href="data:image/jpeg;base64, <?php echo $favicon?>" type="image/x-icon" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
	</head>
	<body>
		<?php if(isset($_SESSION["nome"])){ ?>
            					<div id="element_to_pop_up">
									<div class="dados">
									<form name="frmAdd" method="post" action="adicionar_animes_script?idanime=<?php echo $animeid; ?>&totalep=<?php echo $episodios; ?>&popup=true"><h3>+ Editar <?php echo $nome;?>:</h3><table>
										<tr>
    										<td>
    											Estado:
											</td>
    										<td>
												<select name="estado" onChange="check(this.value, <?php echo $episodios; ?>)">
												<option value="2" <?php if($add_idestado==2){ ?>selected="selected"<?php } ?>>Assistindo</option>
												<option value="1" <?php if($add_idestado==1){ ?>selected="selected"<?php } ?> >Completo</option>
												<option value="3" <?php if($add_idestado==3){ ?>selected="selected"<?php } ?>>Em espera</option>
												<option value="4" <?php if($add_idestado==4){ ?>selected="selected"<?php } ?>>Desistiu</option>
												<option value="5" <?php if($add_idestado==5){ ?>selected="selected"<?php } ?>>Planeia assistir</option>
												</select>
											</td>
  										</tr>
  										<tr>
    										<td>
    											Episódios vistos:
											</td>
    										<td>
												<input type="number" name="episodios" id="episodios_vistos" min="0" max="<?php echo $episodios;?>" placeholder="Ep." style="width:65px;display:contents;" value="<?php echo $add_episodiosvistos ?>">/<?php echo $episodios;?>
											</td>
  										</tr>
   										<tr>
    										<td>
    											Nota:
											</td>
    										<td>
												<select name="nota">
												<option value="NULL">Escolher nota</option>
												<option value="10" <?php if($add_nota==10){ ?>selected="selected"<?php } ?>>(10) Perfeito</option>
												<option value="9" <?php if($add_nota==9){ ?>selected="selected"<?php } ?>>(9) Excelente</option>
												<option value="8" <?php if($add_nota==8){ ?>selected="selected"<?php } ?>>(8) Ótimo</option>
												<option value="7" <?php if($add_nota==7){ ?>selected="selected"<?php } ?>>(7) Muito bom</option>
												<option value="6" <?php if($add_nota==6){ ?>selected="selected"<?php } ?>>(6) Bom</option>
												<option value="5" <?php if($add_nota==5){ ?>selected="selected"<?php } ?>>(5) Médio</option>
												<option value="4" <?php if($add_nota==4){ ?>selected="selected"<?php } ?>>(4) Mau</option>
												<option value="3" <?php if($add_nota==3){ ?>selected="selected"<?php } ?>>(3) Muito mau</option>
												<option value="2" <?php if($add_nota==2){ ?>selected="selected"<?php } ?>>(2) Horrível</option>
												<option value="1" <?php if($add_nota==1){ ?>selected="selected"<?php } ?>>(1) Péssimo</option>
												</select>
											</td>
  										</tr>
  										<tr>
  											<td colspan="2">
  												<input type="submit" name="adicionar" style="font-size: 15px;" value="Adicionar">
  												<input type="submit" name="apagar" style="font-size: 15px;" value="Remover">
  											</td>
  										</tr>
									</table></form>
									</div>
            					</div><?php } ?>
            					<style>
		/* Button */

	input[type="submit"],
	input[type="reset"],
	input[type="button"],
	.button {
		-moz-appearance: none;
		-webkit-appearance: none;
		-o-appearance: none;
		-ms-appearance: none;
		appearance: none;
		-moz-transition: background-color 0.35s ease-in-out;
		-webkit-transition: background-color 0.35s ease-in-out;
		-o-transition: background-color 0.35s ease-in-out;
		-ms-transition: background-color 0.35s ease-in-out;
		transition: background-color 0.35s ease-in-out;
		background: #4D4D4D;
		border-radius: 6px;
		border: 0;
		color: white;
		cursor: pointer;
		display: inline-block;
		padding: 0.75em 1.5em;
		text-align: center;
		text-decoration: none;
		text-transform: uppercase;
		display: inline-block;
		font-size: 1.2em;
		font-weight: 600;
		color: #FFF !important;
	}
		input[type="submit"]:hover,
		input[type="reset"]:hover,
		input[type="button"]:hover,
		.button:hover {
			background: #757575;
		}
		input[type="submit"].alt,
		input[type="reset"].alt,
		input[type="button"].alt,
		.button.alt {
			border: 1px solid #FFF;
			color: #0f1116;
		}
		input[type="submit"].fit,
		input[type="reset"].fit,
		input[type="button"].fit,
		.button.fit {
			width: 100%;
		}
		input[type="submit"].small,
		input[type="reset"].small,
		input[type="button"].small,
		.button.small {
			font-size: 0.6em;
		}
		input[type="submit"].medium,
		input[type="reset"].medium,
		input[type="button"].medium,
		.button.medium {
			padding: 0.80em 2em;
			font-size: 1.6em;
		}						
.dados { 
	height:auto;
	margin:auto;
	padding:10px;
	background-color: white;
	border-radius: 6px;
}

.dados input { 
	width: inherit;
	height:30px;
	padding:5px;
	margin:5px;
	border:1px #CCC solid;
}

.dados input[type="submit"] {
	font-size: 20px;
	padding: 0.5em;
	height: auto;
}

.dados select { 
	width: inherit;
	height:31px;
	padding:5px;
	margin:5px;
	border:1px #CCC solid;
}

.dados th{
	text-align: center;
	font-weight: bold;
}

.dados table{
	width: auto;
}
</style>
	</body>
</html>