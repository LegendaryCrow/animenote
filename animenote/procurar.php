<!DOCTYPE HTML>
<html>
	<head>
	<?php
	session_start();
    header('Content-Type: text/html; charset=UTF-8');
    $con=new mysqli ("localhost","root","","animenote");
    mysqli_set_charset($con, 'utf8mb4');
	
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
	
    if($con->connect_error){
        echo $con->connect_errno;
        die("Database Connection Failed");
    }
	if (isset($_GET['search'])){
		$procura=$_GET['search'];
	}else{
		$procura="";
	}
	if (isset($_GET['type'])){
		$tipo_procura=$_GET['type'];
	}else{
		$tipo_procura="animes";
	}
	if (isset($_GET['pagina'])){
		$pagina=$_GET['pagina'];
	}else{
		$pagina=1;
	}
	
	//Filtros
	if (isset($_GET['genero'])){
		for($i=0; $i<count($_GET['genero']); $i++){
			$filtro_genero[]=$_GET['genero'][$i];
		}
	}else{
		$filtro_genero[]="";
	}
	if (isset($_GET['ungenero'])){
		for($i=0; $i<count($_GET['ungenero']); $i++){
			$filtro_ungenero[]=$_GET['ungenero'][$i];
		}
	}else{
		$filtro_ungenero[]="";
	}
	if (isset($_GET['tipo'])){
		$filtro_tipo=$_GET['tipo'];
	}else{
		$filtro_tipo="";
	}
	if (isset($_GET['nota'])){
		if($_GET['nota']=="" || $_GET['nota']==0){
			$filtro_nota1=0;
			$filtro_nota2=10;
		}else{
			$filtro_nota1=$_GET['nota'];
			$filtro_nota2=$filtro_nota1+1;
		}
	}else{
		$filtro_nota1=0;
		$filtro_nota2=10;
	}
	if(isset($_GET['produtores'])){
	if (is_numeric($_GET['produtores'])){
		$filtro_produtores=$_GET['produtores'];
			$produtores_condicao=" AND animes.id_animes LIKE animes_produtores.id_animes AND animes_produtores.id_produtores LIKE produtores.id_produtores AND produtores.id_produtores like $filtro_produtores";
			$produtores_from=", produtores, animes_produtores";
	}else{
		$filtro_produtores="";
		$produtores_condicao="";
		$produtores_from="";
	}
	}else{
		$filtro_produtores="";
		$produtores_condicao="";
		$produtores_from="";
	}
	if(isset($_GET['licenciadores'])){
	if (is_numeric($_GET['licenciadores'])){
		$filtro_licenciadores=$_GET['licenciadores'];
			$licenciadores_condicao=" AND animes.id_animes LIKE animes_licenciadores.id_animes AND animes_licenciadores.id_licenciadores LIKE licenciadores.id_licenciadores AND licenciadores.id_licenciadores like $filtro_licenciadores";
			$licenciadores_from=", licenciadores, animes_licenciadores";
	}else{
		$filtro_licenciadores="";
		$licenciadores_condicao="";
		$licenciadores_from="";
	}
	}else{
		$filtro_licenciadores="";
		$licenciadores_condicao="";
		$licenciadores_from="";
	}
	if(isset($_GET['estudio'])){
	if (is_numeric($_GET['estudio'])){
		$filtro_estudio=$_GET['estudio'];
			$estudio_condicao=" AND animes.id_animes LIKE animes_estudio.id_animes AND animes_estudio.id_estudio LIKE estudio.id_estudio AND estudio.id_estudio LIKE $filtro_estudio";
			$estudio_from=", estudio, animes_estudio";
	}else{
		$filtro_estudio="";
		$estudio_condicao="";
		$estudio_from="";
	}
	}else{
		$filtro_estudio="";
		$estudio_condicao="";
		$estudio_from="";
	}
	if(isset($_GET['classificacao'])){
	if (is_numeric($_GET['classificacao'])){
		$filtro_classificacao=$_GET['classificacao'];
	}else{
		$filtro_classificacao="";
	}
	}else{
		$filtro_classificacao="";
	}
	
	$quantpag=$procurar_quantidade; //Quantidade de animes por pagina
	//Retirar informação da tabela animes
		if($tipo_procura=="animes"){
			$sqlget = "SELECT animes.* FROM animes$produtores_from $licenciadores_from $estudio_from WHERE animes.nome like '%$procura%' AND animes.id_tipo LIKE '%$filtro_tipo%' AND animes.id_classificacao LIKE '%$filtro_classificacao%' $produtores_condicao $licenciadores_condicao $estudio_condicao AND nota_media BETWEEN $filtro_nota1 AND $filtro_nota2 ORDER BY `animes`.`nome` ASC";
    		$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
			while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
			if(isset($row['id_animes'])){
        	$idanimes[] = $row['id_animes'] ;
        	$nome[] = $row['nome'] ;
        	$episodios[] = $row['episodios'] ;
			$imagem[] = base64_encode( $row['imagem'] ) ;
		    $id_tipo[] = $row['id_tipo'] ;
		    $nota[] = $row['nota_media'] ;
			$id_tipo1 = $row['id_tipo'];
			//Retirar informação da tabela tipo
			$sqlget1 = "SELECT * FROM tipo where id_tipo like $id_tipo1";
			$sqldata1 = mysqli_query($con,$sqlget1) or die ('error getting database');
			while ($row1 = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC)) {
        		$tipo[] = $row1['nome'] ;
    		}
			}
    		}
			$genero_confirma=0;
			if(isset($idanimes)){
				$totalanimes=count($idanimes);
			}else{
				$totalanimes=0;
			}
			for($i=0; $i<$totalanimes; $i++){
				$sqlget = "SELECT animes_generos.id_generos FROM animes, animes_generos WHERE animes.id_animes LIKE $idanimes[$i] AND animes_generos.id_animes LIKE animes.id_animes";
    			$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
				while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
					for($w=0; $w<count($filtro_ungenero); $w++){
						if($row['id_generos']==$filtro_ungenero[$w]){
							unset($idanimes[$i]);
							$idanimes=array_values($idanimes);
							unset($nome[$i]);
							$nome=array_values($nome);
							unset($episodios[$i]);
							$episodios=array_values($episodios);
							unset($imagem[$i]);
							$imagem=array_values($imagem);
							unset($id_tipo[$i]);
							$id_tipo=array_values($id_tipo);
							unset($tipo[$i]);
							$tipo=array_values($tipo);
							$totalanimes=count($idanimes);
							$genero_confirma=1;
						}
					}
				}if($genero_confirma==1){
						$i--;
						$genero_confirma=0;
					}
			}
			$genero_confirma=0;
			if(isset($idanimes)){
				$totalanimes=count($idanimes);
			}else{
				$totalanimes=0;
			}
			if(is_numeric($filtro_genero[0])){
			for($i=0; $i<$totalanimes; $i++){
				$sqlget = "SELECT animes_generos.id_generos FROM animes, animes_generos WHERE animes.id_animes LIKE $idanimes[$i] AND animes_generos.id_animes LIKE animes.id_animes";
    			$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
				while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
					for($w=0; $w<count($filtro_genero); $w++){
						if($row['id_generos']==$filtro_genero[$w]){
							$genero_confirma++;
						}
					}
				}
				if($genero_confirma!=count($filtro_genero)){
					unset($idanimes[$i]);
					$idanimes=array_values($idanimes);
					unset($nome[$i]);
					$nome=array_values($nome);
					unset($episodios[$i]);
					$episodios=array_values($episodios);
					unset($imagem[$i]);
					$imagem=array_values($imagem);
					unset($id_tipo[$i]);
					$id_tipo=array_values($id_tipo);
					unset($tipo[$i]);
					$tipo=array_values($tipo);
					$i--;
					$totalanimes=count($idanimes);
				}
				$genero_confirma=0;
			}
		}
		}
		if($tipo_procura=="personagens"){
			$sqlget = "SELECT * FROM personagens where nome like '%$procura%' ORDER BY `personagens`.`nome` ASC ";
    		$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
			while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
			if(isset($row['id_personagens'])){
        	$idpersonagens[] = $row['id_personagens'] ;
        	$nome[] = $row['nome'] ;
			$imagem[] = base64_encode( $row['imagem'] ) ;
			}
    		}
		}
		if($tipo_procura=="pessoas"){
			$sqlget = "SELECT * FROM pessoa where nome like '%$procura%' ORDER BY `pessoa`.`nome` ASC ";
    		$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
			while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
			if(isset($row['id_pessoa'])){
        	$idpessoa[] = $row['id_pessoa'] ;
        	$nome[] = $row['nome'] ;
			$imagem[] = base64_encode( $row['imagem'] ) ;
			}
    		}
		}
		if($tipo_procura=="utilizadores"){
			$sqlget = "SELECT * FROM utilizador where nome like '%$procura%' and ativacao like '0' ORDER BY `utilizador`.`nome` ASC ";
    		$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
			while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
			if(isset($row['id_utilizador'])){
        	$idutilizador[] = $row['id_utilizador'] ;
        	$nome[] = $row['nome'] ;
			$localizacao[] = $row['localizacao'] ;
			$imagem[] = base64_encode( $row['imagem'] ) ;
			}
    		}
		}
	if(isset($nome)){
		$totalpag=count($nome)/$quantpag;
	}else{
		$totalpag=1;
	}
	if($pagina>$totalpag+1){
		header('Location: procurar');
	}
	//Retirar informação da tabela generos
	$sqlget = "SELECT * FROM `generos` ORDER BY `generos`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_generos[] = $row['id_generos'];
		$nome_generos[] = $row['nome'];
    }
	$total_generos=count($nome_generos);
	$colunas_generos=8;
	$linhas_generos=$total_generos/$colunas_generos;
	//Retirar informação da tabela tipo
	$sqlget = "SELECT * FROM `tipo` ORDER BY `tipo`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_tipoanime[] = $row['id_tipo'];
		$nome_tipoanime[] = $row['nome'];
    }
	$total_tipoanime=count($nome_tipoanime);
	//Retirar informação da tabela produtores
	$sqlget = "SELECT * FROM `produtores` ORDER BY `produtores`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_produtores[] = $row['id_produtores'];
		$nome_produtores[] = $row['nome'];
    }
	$total_produtores=count($nome_produtores);
	//Retirar informação da tabela licenciadores
	$sqlget = "SELECT * FROM `licenciadores` ORDER BY `licenciadores`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_licenciadores[] = $row['id_licenciadores'];
		$nome_licenciadores[] = $row['nome'];
    }
	$total_licenciadores=count($nome_licenciadores);
	//Retirar informação da tabela estudio
	$sqlget = "SELECT * FROM `estudio` ORDER BY `estudio`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_estudio[] = $row['id_estudio'];
		$nome_estudio[] = $row['nome'];
    }
	$total_estudio=count($nome_estudio);
	//Retirar informação da tabela classificacao
	$sqlget = "SELECT * FROM `classificacao` ORDER BY `classificacao`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_classificacao[] = $row['id_classificacao'];
		$nome_classificacao[] = $row['nome'];
    }
	$total_classificacao=count($nome_classificacao);
	//Retirar informação da tabela mensagens (notificação)
	$novas_mensagens=0;
	if(isset($_SESSION["id_utilizador"])){
	$sqlget = "SELECT * FROM `mensagens` WHERE id_utilizador2 like ".$_SESSION["id_utilizador"]." AND checked like 0";
    $sqldata = mysqli_query($con,$sqlget) ;
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$novas_mensagens++;
    }
	}
	//Retirar informação da tabela lista_amigos (notificação)
	$pedidos_amizade=0;
	if(isset($_SESSION["id_utilizador"])){
	$sqlget = "SELECT * FROM `lista_amigos` WHERE id_utilizador2 like ".$_SESSION["id_utilizador"]." AND confirmacao like 1";
    $sqldata = mysqli_query($con,$sqlget) ;
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$pedidos_amizade++;
	}
	}
    ?>
		<title>Procurar - <?php echo $nome_website; ?></title>
		<link rel="shortcut icon" href="data:image/jpeg;base64, <?php echo $favicon?>" type="image/x-icon" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<script src="js/dropdown-menu.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-wide.css" />
		</noscript>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
	</head>
	<body>
		<a href="#" class="scrollToTop"><img src="images/arrow_up_trans.png"></a>

		<!-- Wrapper -->
			<div class="wrapper style1">

				<!-- Header -->
					<div id="header" class="skel-panels-fixed">
						<div id="logo">
							<h1><a href="index.html"><?php echo $nome_website; ?></a></h1>
							<span class="tag"><?php echo $slogan_website; ?></span>
						</div>
						<nav id="nav">
							<ul>
								<li class="active"><a href="index">Principal</a></li>
								<li><a href="procurar">Procurar</a></li>
								<li><a href="classificacao">Classificação</a></li>
								<?php if(isset($_SESSION["nome"])) {?>
								<li><a href="javascript:void(0)" class="dropbtn" onclick="myFunction()"><?php echo $_SESSION["nome"]; ?> ⬇️   </a>
								<a href="mensagens" class="icon"><i class="fa fa-envelope"></i><?php if($novas_mensagens>0){ ?><span class="badge"><?php echo $novas_mensagens; ?></span><?php } ?></a>
								<a href="amigos" class="icon"><i class="fa fa-bell"></i><?php if($pedidos_amizade>0){ ?><span class="badge"><?php echo $pedidos_amizade; ?></span><?php } ?></a>
									<div class="dropdown-content" id="myDropdown">
      									<a href="perfil">Perfil</a><br>
      									<a href="lista_animes">Minha Lista</a><br>
      									<a href="editar_perfil">Definições</a><br>
      									<a href="logout_script">Sair</a><br>
    								</div>
    							</li>
    							<?php }else{ ?>
								<li><a href="login">Iniciar sessão</a></li>
								<?php } ?>
							</ul>
						</nav>
					</div>
				<!-- Header -->

				<!-- Page -->
					<div id="page" class="container">
						<section>
							<form class="procura">
								<div class="aba1">
								<input type="procurar" name="search" placeholder="Procurar...">
								<select type="procurar" name="type" onchange="this.form.submit()">
									<option value="animes" <?php if($tipo_procura=="animes"){?>selected="selected"<?php } ?>>Animes</option>
									<option value="personagens" <?php if($tipo_procura=="personagens"){?>selected="selected"<?php } ?>>Personagens</option>
									<option value="pessoas" <?php if($tipo_procura=="pessoas"){?>selected="selected"<?php } ?>>Pessoas</option>
									<option value="utilizadores" <?php if($tipo_procura=="utilizadores"){?>selected="selected"<?php } ?>>Utilizadores</option>
								</select>
								<input type="submit" value="procurar" style="display: none"><?php if($tipo_procura=="animes"){?>
								<img src="images/filtro.png" onClick="procurar_opcoes()" style="cursor: pointer; width: 30px;"><?php } ?>
								</div>
								<?php if($tipo_procura=="animes"){?>
								<div id="procura-avancadas"  style="display: none">
								<div class="clearfix">
								<div class="normal_header">Filtros</div>
								<div class="left">
								<table class="procura">
									<tr>
										<td>
											Tipo
										</td>
										<td>
											<select name="tipo" id="selectBox">
												<option value="">Escolher tipo</option>
												<?php for ($i=0; $i<$total_tipoanime; $i++) { ?>
												<option value="<?php echo $id_tipoanime[$i];?>" <?php if($filtro_tipo==$id_tipoanime[$i]){?>selected="selected"<?php } ?>><?php echo $nome_tipoanime[$i];?></option><?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>
											Nota
										</td>
										<td>
											<select name="nota" id="selectBox">
												<option value="" <?php if($filtro_nota1==0){?>selected="selected"<?php } ?>>Escolher nota</option>
												<option value="10" <?php if($filtro_nota1==10){?>selected="selected"<?php } ?>>(10) Perfeito</option>
												<option value="9" <?php if($filtro_nota1==9){?>selected="selected"<?php } ?>>(9) Excelente</option>
												<option value="8" <?php if($filtro_nota1==8){?>selected="selected"<?php } ?>>(8) Ótimo</option>
												<option value="7" <?php if($filtro_nota1==7){?>selected="selected"<?php } ?>>(7) Muito bom</option>
												<option value="6" <?php if($filtro_nota1==6){?>selected="selected"<?php } ?>>(6) Bom</option>
												<option value="5" <?php if($filtro_nota1==5){?>selected="selected"<?php } ?>>(5) Médio</option>
												<option value="4" <?php if($filtro_nota1==4){?>selected="selected"<?php } ?>>(4) Mau</option>
												<option value="3" <?php if($filtro_nota1==3){?>selected="selected"<?php } ?>>(3) Muito mau</option>
												<option value="2" <?php if($filtro_nota1==2){?>selected="selected"<?php } ?>>(2) Horrível</option>
												<option value="1" <?php if($filtro_nota1==1){?>selected="selected"<?php } ?>>(1) Péssimo</option>
												</select>
										</td>
									</tr>
									<tr>
										<td>
											Produtores
										</td>
										<td>
											<select name="produtores" id="selectBox">
												<option value="">Escolher produtor</option>
												<?php for ($i=0; $i<$total_produtores; $i++) { ?>
												<option value="<?php echo $id_produtores[$i];?>" <?php if($filtro_produtores==$id_produtores[$i]){?>selected="selected"<?php } ?>><?php echo $nome_produtores[$i];?></option><?php } ?>
											</select>
										</td>
									</tr>
								</table>
								</div>
								<div class="right">
								<table class="procura">
									<tr>
										<td>
											Licenciadores
										</td>
										<td>
											<select name="licenciadores" id="selectBox">
												<option value="">Escolher licenciador</option>
												<?php for ($i=0; $i<$total_licenciadores; $i++) { ?>
												<option value="<?php echo $id_licenciadores[$i];?>" <?php if($filtro_licenciadores==$id_licenciadores[$i]){?>selected="selected"<?php } ?>><?php echo $nome_licenciadores[$i];?></option><?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>
											Estudio
										</td>
										<td>
											<select name="estudio" id="selectBox">
												<option value="">Escolher estudio</option>
												<?php for ($i=0; $i<$total_estudio; $i++) { ?>
												<option value="<?php echo $id_estudio[$i];?>" <?php if($filtro_estudio==$id_estudio[$i]){?>selected="selected"<?php } ?>><?php echo $nome_estudio[$i];?></option><?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>
											Classificação
										</td>
										<td>
											<select name="classificacao" id="selectBox">
												<option value="">Escolher classificação</option>
												<?php for ($i=0; $i<$total_classificacao; $i++) { ?>
												<option value="<?php echo $id_classificacao[$i];?>" <?php if($filtro_classificacao==$id_classificacao[$i]){?>selected="selected"<?php } ?>><?php echo $nome_classificacao[$i];?></option><?php } ?>
											</select>
										</td>
									</tr>
								</table>
								</div>
								</div>
								<div class="normal_header">Filtros de Géneros <a href="info_generos" onClick="window.open(this.href,'Informação','resizable,height=400,width=300'); return false;">(Ajuda)</a></div>
								<div class="generos">
								<table>
								<?php $w=0; for ($i=0; $i<$linhas_generos; $i++) { ?>
									<tr>
										<?php while($w<$total_generos && $w<$colunas_generos){ $name_genero=0; ?>
										<td>
											<input type="checkbox" <?php for($x=0; $x<count($filtro_ungenero); $x++){ if($id_generos[$w]==$filtro_ungenero[$x]){ ?> name="ungenero[]" checked<?php $name_genero=1;}} ?> <?php for($x=0; $x<count($filtro_genero); $x++){ if($id_generos[$w]==$filtro_genero[$x]){ ?> name="genero[]" checked<?php $name_genero=1;}} ?> <?php if($name_genero==0){ ?>name="genero[]"<?php } ?> value="<?php echo $id_generos[$w]; ?>" id="<?php echo $id_generos[$w]; ?>" onclick="ts(this)"><label for="<?php echo $id_generos[$w]; ?>"  title="✔ Incluir&#10;✗ Excluir" ><?php echo $nome_generos[$w]; ?></label>
										</td>
										<?php $w++;} ?>
									</tr>
								<?php $colunas_generos=$colunas_generos+8;} ?>
									<tr>
										<td colspan="3">
										</td>
										<td>
											<input style="font-size: 15px; vertical-align: middle;" type="button" onClick="uncheckAll()" value="Limpar">
										</td>
										<td>
											<input style="font-size: 15px; vertical-align: middle;" type="submit" value="Procurar">
										</td>
										<td colspan="3">
										</td>
									</tr>
								</table>
								</div>
								</div><?php } ?>
							</form>
							<p>
								<table class="listar">
										<?php if($procura!=""){ ?><tr><th colspan="4"><center><b>Procurar resulados de <?php echo $tipo_procura; ?> para '<?php echo $procura; ?>'</b></center></th></tr><?php } ?>
										<tr>
											<?php if($tipo_procura=="animes"){ ?>
											<th>#</th>
											<th>Título</th>
											<th>Tipo</th>
											<th>Eps.</th>
											<th>Nota</th>
											<?php }if($tipo_procura=="personagens"){ ?>
											<th>#</th>
											<th>Nome</th>
											<?php }if($tipo_procura=="pessoas"){ ?>
											<th>#</th>
											<th>Nome</th>
											<?php }if($tipo_procura=="utilizadores"){ ?>
											<th>#</th>
											<th>Nome</th>
											<th>Localizacao</th>
											<?php } ?>
										</tr>
										<?php $maximo=$pagina*$quantpag;$minimo=$maximo-$quantpag;
										if(isset($nome)){ while(count($nome)>$minimo && $minimo<$maximo){ ?>
										<tr>
    										<td width="55">
    											<?php if (empty($imagem[$minimo])) { ?>
													<img src="images/user_noimage.png" width="50" vspace="4" border="0" hspace="8">
												<?php }else{ ?>
													<img src="data:image/jpeg;base64, <?php echo $imagem[$minimo]?>" width="50" vspace="4" border="0" hspace="8">
												<?php } ?>
											</td>
   											<?php if($tipo_procura=="animes"){ ?>
   											<td width="100%">
												<a href="anime_informacao?idanime=<?php echo $idanimes[$minimo];?>"><?php echo $nome[$minimo];?></a>
											</td>
											<td>
												<?php echo $tipo[$minimo];?>
											</td>
											<td>
												<?php echo $episodios[$minimo];?>
											</td>
											<td>
												<?php echo $nota[$minimo];?>
											</td>
											<?php }if($tipo_procura=="personagens"){ ?>
											<td width="100%">
												<a href="personagem_informacao?idpersonagem=<?php echo $idpersonagens[$minimo];?>"><?php echo $nome[$minimo];?></a>
											</td>
											<?php }if($tipo_procura=="pessoas"){ ?>
											<td width="100%">
												<a href="pessoa_informacao?idpessoa=<?php echo $idpessoa[$minimo];?>"><?php echo $nome[$minimo];?></a>
											</td>
											<?php }if($tipo_procura=="utilizadores"){ ?>
											<td width="100%">
												<a href="perfil?utilizador=<?php echo $nome[$minimo];?>"><?php echo $nome[$minimo];?></a>
											</td>
											<td>
												<?php echo $localizacao[$minimo];?>
											</td>
											<?php } ?>
  										</tr>
  										<?php $minimo++;}} ?>
									</table>
									<div class="center">
									<div class="pagination">
  										<a <?php if($pagina>1){ ?>href="procurar?pagina=<?php echo $pagina-1; if($procura!=""){ ?>&search=<?php echo $procura; }if($tipo_procura!=""){ ?>&type=<?php echo $tipo_procura; } ?>&tipo=<?php echo $filtro_tipo; ?>&nota=<?php echo $filtro_nota1; ?>&produtores=<?php echo $filtro_produtores; ?>&licenciadores=<?php echo $filtro_licenciadores; ?>&estudio=<?php echo $filtro_estudio; ?>&classificacao=<?php echo $filtro_classificacao; ?>"<?php } ?>>&laquo;</a>
  										
  										<a href="procurar?pagina=1<?php if($procura!=""){ ?>&search=<?php echo $procura; }if($tipo_procura!=""){ ?>&type=<?php echo $tipo_procura; }?>&tipo=<?php echo $filtro_tipo; ?>&nota=<?php echo $filtro_nota1; ?>&produtores=<?php echo $filtro_produtores; ?>&licenciadores=<?php echo $filtro_licenciadores; ?>&estudio=<?php echo $filtro_estudio; ?>&classificacao=<?php echo $filtro_classificacao; ?>" <?php if($pagina=='1'){ ?>class="active"<?php } ?>>1</a>
  										
  										<?php if(isset($nome)){$totalpag=count($nome)/$quantpag;$w=1;
										while($totalpag>$w){ ?>
  										<a href="procurar?pagina=<?php echo $w+1; if($procura!=""){ ?>&search=<?php echo $procura; }if($tipo_procura!=""){ ?>&type=<?php echo $tipo_procura; }?>&tipo=<?php echo $filtro_tipo; ?>&nota=<?php echo $filtro_nota1; ?>&produtores=<?php echo $filtro_produtores; ?>&licenciadores=<?php echo $filtro_licenciadores; ?>&estudio=<?php echo $filtro_estudio; ?>&classificacao=<?php echo $filtro_classificacao; ?>" <?php if($pagina==$w+1){ ?>class="active"<?php } ?>><?php echo $w+1 ?></a>
  										
  										<?php $w++;}} ?>
  										<a <?php if($pagina<$totalpag){ ?>href="procurar?pagina=<?php echo $pagina+1; if($procura!=""){ ?>&search=<?php echo $procura; }if($tipo_procura!=""){ ?>&type=<?php echo $tipo_procura; }?>&tipo=<?php echo $filtro_tipo; ?>&nota=<?php echo $filtro_nota1; ?>&produtores=<?php echo $filtro_produtores; ?>&licenciadores=<?php echo $filtro_licenciadores; ?>&estudio=<?php echo $filtro_estudio; ?>&classificacao=<?php echo $filtro_classificacao; ?>"<?php } ?>>&raquo;</a>
									</div>
									</div>
							</p>
						</section>
					</div>
				<!-- /Page -->
	</div>
	<!-- Copyright -->
		<div id="copyright">
			<div class="container"> <span class="copyright"><?php echo $copyright_website; ?></span>
			</div>
		</div>

	</body>
</html>