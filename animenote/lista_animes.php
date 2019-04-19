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
	
    if($con->connect_error)
    {
        echo $con->connect_errno;
        die("Database Connection Failed");
    }
	if(empty($_GET['utilizador'])){
		if(isset($_SESSION["nome"])) {
			$procurar_utilizador=$_SESSION["nome"];
			//Retirar informação da tabela utilizador
    		$sqlget = "SELECT * FROM utilizador where nome='" . $_SESSION["nome"] . "' and password='" . $_SESSION["password"] . "'";
    		$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
			$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
			$id_utilizador = $row['id_utilizador'];
    		$nome = $row['nome'];
    		$genero = $row['genero'];
			//Query lista_animes
			$sqlget = "SELECT * FROM `lista_animes` WHERE `id_utilizador`='" . $_SESSION["id_utilizador"] . "'";
		}else{
			header('Location: login');
			exit();
		}
	}else{
		$procurar_utilizador=$_GET['utilizador'];
		//Retirar informação da tabela utilizador
    	$sqlget = "SELECT * FROM utilizador where nome='" . $procurar_utilizador . "' and ativacao like '0'";
    	$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
		$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
		$id_utilizador = $row['id_utilizador'];
    	$nome = $row['nome'];
    	$genero = $row['genero'];
		//Query lista_animes
		$sqlget = "SELECT * FROM `lista_animes` WHERE `id_utilizador`='" . $id_utilizador . "'";
	}
	if($nome==""){
		header('Location: lista_animes');
		exit();
	}
	//Retirar informação da tabela lista_animes
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
        $idanimes[] = $row['id_animes'] ;
        $idestado[] = $row['id_estado'] ;
        $episodios_vistos[] = $row['episodios_vistos'] ;
		$nota[] = $row['nota'];
    }
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
		<title>Lista <?php if($genero=="Masculino"){echo "do";}elseif($genero=="Feminino"){echo "da";}elseif($genero=="Desconhecido"){echo "do/a";} ?> <?php echo $procurar_utilizador; ?> - <?php echo $nome_website; ?></title>
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
						
							<header class="major">
								<div class="titulo"><h2>Lista <?php if($genero=="Masculino"){echo "do";}elseif($genero=="Feminino"){echo "da";}elseif($genero=="Desconhecido"){echo "do/a";} ?> <?php echo $nome; ?></h2></div>
							</header>
						
					<div class="tab-button-wrapper">
						<ul>
							<li><a class="tab-button-first"
								id="tab-button1"
								href="javascript:void(0)"
								onclick="loadTab(1)">Assistindo</a></li>
							<li><a class="tab-button-hidden" 
								id="tab-button2"
								href="javascript:void(0)"
								onclick="loadTab(2)">Completos</a></li>
							<li><a class="tab-button-hidden" 
								id="tab-button3"
								href="javascript:void(0)"
								onclick="loadTab(3)">Em espera</a></li>
							<li><a class="tab-button-hidden" 
								id="tab-button4"
								href="javascript:void(0)"
								onclick="loadTab(4)">Desistiu</a></li>
							<li><a class="tab-button-hidden tab-button-last" 
								id="tab-button5"
								href="javascript:void(0)"
								onclick="loadTab(5)">Planeia assistir</a></li>
						</ul>
					</div>
					<div class="tab-body-wrapper">
						<div class="tab-body" id="tab1"><p>
									<table class="listar">
										<tr>
											<th>#</th>
											<th>Título</th>
											<th>Estado</th>
											<th>Episódios Vistos</th>
											<th>Nota</th>
										</tr>
										<?php if(isset($idanimes)){ $i=0; while(count($idanimes)>$i){if($idestado[$i]==2){
										//Retirar informação da tabela animes
										$sqlget = "SELECT * FROM `animes` WHERE `id_animes` like $idanimes[$i] ORDER BY `animes`.`nome` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    									$idanime = $row['id_animes'] ;
    									$nome = $row['nome'] ;
    									$episodios = $row['episodios'] ;
										$imagem = base64_encode( $row['imagem'] ) ;
										//Retirar informação da tabela estado
										$sqlget = "SELECT * FROM `estado` WHERE `id_estado` like $idestado[$i]";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    									$estado = $row['nome'] ; ?>
										<tr>
    										<td width="55">
    											<img src="data:image/jpeg;base64, <?php echo $imagem?>" width="50" vspace="4" border="0" hspace="8">
											</td>
    										<td width="100%">
												<a href="anime_informacao?idanime=<?php echo $idanime;?>"><?php echo $nome;?></a>
												<?php if(isset($_SESSION["nome"])){if($_SESSION["nome"]==$procurar_utilizador){ ?><a class="icon-a" title="Editar anime" href="editar_animes?idanime=<?php echo $idanime;?>" onClick="window.open(this.href,'Editar Anime','resizable,height=300,width=300'); return false;">🖋</a><?php }} ?>
											</td>
											<td>
												<?php echo $estado;?>
											</td>
											<td>
												<?php echo $episodios_vistos[$i] ?>/<?php echo $episodios;?>
											</td>
											<td>
												<?php echo $nota[$i];?>
											</td>
  										</tr>
  										<?php }$i++;}} ?>
									</table>
						</p></div>
						<div class="tab-body tab-body-hidden" id="tab2"><p>
									<table class="listar">
										<tr>
											<th>#</th>
											<th>Título</th>
											<th>Estado</th>
											<th>Episódios Vistos</th>
											<th>Nota</th>
										</tr>
										<?php if(isset($idanimes)){ $i=0; while(count($idanimes)>$i){if($idestado[$i]==1){
										//Retirar informação da tabela animes
										$sqlget = "SELECT * FROM `animes` WHERE `id_animes` like $idanimes[$i] ORDER BY `animes`.`nome` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    									$idanime = $row['id_animes'] ;
    									$nome = $row['nome'] ;
    									$episodios = $row['episodios'] ;
										$imagem = base64_encode( $row['imagem'] ) ;
										//Retirar informação da tabela estado
										$sqlget = "SELECT * FROM `estado` WHERE `id_estado` like $idestado[$i]";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    									$estado = $row['nome'] ; ?>
										<tr>
    										<td width="55">
    											<img src="data:image/jpeg;base64, <?php echo $imagem?>" width="50" vspace="4" border="0" hspace="8">
											</td>
    										<td width="100%">
												<a href="anime_informacao?idanime=<?php echo $idanime;?>"><?php echo $nome;?></a>
												<?php  if(isset($_SESSION["nome"])){if($_SESSION["nome"]==$procurar_utilizador){ ?><a class="icon-a" title="Editar anime" href="editar_animes?idanime=<?php echo $idanime;?>" onClick="window.open(this.href,'Editar Anime','resizable,height=300,width=300'); return false;">🖋</a><?php }} ?>
											</td>
											<td>
												<?php echo $estado;?>
											</td>
											<td>
												<?php echo $episodios_vistos[$i] ?>/<?php echo $episodios;?>
											</td>
											<td>
												<?php echo $nota[$i];?>
											</td>
  										</tr>
  										<?php }$i++;}} ?>
									</table>
						</p></div>
						<div class="tab-body tab-body-hidden" id="tab3"><p>
									<table class="listar">
										<tr>
											<th>#</th>
											<th>Título</th>
											<th>Estado</th>
											<th>Episódios Vistos</th>
											<th>Nota</th>
										</tr>
										<?php if(isset($idanimes)){ $i=0; while(count($idanimes)>$i){if($idestado[$i]==3){
										//Retirar informação da tabela animes
										$sqlget = "SELECT * FROM `animes` WHERE `id_animes` like $idanimes[$i] ORDER BY `animes`.`nome` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    									$idanime = $row['id_animes'] ;
    									$nome = $row['nome'] ;
    									$episodios = $row['episodios'] ;
										$imagem = base64_encode( $row['imagem'] ) ;
										//Retirar informação da tabela estado
										$sqlget = "SELECT * FROM `estado` WHERE `id_estado` like $idestado[$i]";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    									$estado = $row['nome'] ; ?>
										<tr>
    										<td width="55">
    											<img src="data:image/jpeg;base64, <?php echo $imagem?>" width="50" vspace="4" border="0" hspace="8">
											</td>
    										<td width="100%">
												<a href="anime_informacao?idanime=<?php echo $idanime;?>"><?php echo $nome;?></a>
												<?php if($_SESSION["nome"]==$procurar_utilizador){ ?><a class="icon-a" title="Editar anime" href="editar_animes?idanime=<?php echo $idanime;?>" onClick="window.open(this.href,'Editar Anime','resizable,height=300,width=300'); return false;">🖋</a><?php } ?>
											</td>
											<td>
												<?php echo $estado;?>
											</td>
											<td>
												<?php echo $episodios_vistos[$i] ?>/<?php echo $episodios;?>
											</td>
											<td>
												<?php echo $nota[$i];?>
											</td>
  										</tr>
  										<?php }$i++;}} ?>
									</table>
						</p></div>
						<div class="tab-body tab-body-hidden" id="tab4"><p>
									<table class="listar">
										<tr>
											<th>#</th>
											<th>Título</th>
											<th>Estado</th>
											<th>Episódios Vistos</th>
											<th>Nota</th>
										</tr>
										<?php if(isset($idanimes)){ $i=0; while(count($idanimes)>$i){if($idestado[$i]==4){
										//Retirar informação da tabela animes
										$sqlget = "SELECT * FROM `animes` WHERE `id_animes` like $idanimes[$i] ORDER BY `animes`.`nome` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    									$idanime = $row['id_animes'] ;
    									$nome = $row['nome'] ;
    									$episodios = $row['episodios'] ;
										$imagem = base64_encode( $row['imagem'] ) ;
										//Retirar informação da tabela estado
										$sqlget = "SELECT * FROM `estado` WHERE `id_estado` like $idestado[$i]";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    									$estado = $row['nome'] ; ?>
										<tr>
    										<td width="55">
    											<img src="data:image/jpeg;base64, <?php echo $imagem?>" width="50" vspace="4" border="0" hspace="8">
											</td>
    										<td width="100%">
												<a href="anime_informacao?idanime=<?php echo $idanime;?>"><?php echo $nome;?></a>
												<?php if($_SESSION["nome"]==$procurar_utilizador){ ?><a class="icon-a" title="Editar anime" href="editar_animes?idanime=<?php echo $idanime;?>" onClick="window.open(this.href,'Editar Anime','resizable,height=300,width=300'); return false;">🖋</a><?php } ?>
											</td>
											<td>
												<?php echo $estado;?>
											</td>
											<td>
												<?php echo $episodios_vistos[$i] ?>/<?php echo $episodios;?>
											</td>
											<td>
												<?php echo $nota[$i];?>
											</td>
  										</tr>
  										<?php }$i++;}} ?>
									</table>
						</p></div>
						<div class="tab-body tab-body-hidden" id="tab5"><p>
									<table class="listar">
										<tr>
											<th>#</th>
											<th>Título</th>
											<th>Estado</th>
											<th>Episódios Vistos</th>
											<th>Nota</th>
										</tr>
										<?php if(isset($idanimes)){ $i=0; while(count($idanimes)>$i){if($idestado[$i]==5){
										//Retirar informação da tabela animes
										$sqlget = "SELECT * FROM `animes` WHERE `id_animes` like $idanimes[$i] ORDER BY `animes`.`nome` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    									$idanime = $row['id_animes'] ;
    									$nome = $row['nome'] ;
    									$episodios = $row['episodios'] ;
										$imagem = base64_encode( $row['imagem'] ) ;
										//Retirar informação da tabela estado
										$sqlget = "SELECT * FROM `estado` WHERE `id_estado` like $idestado[$i]";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    									$estado = $row['nome'] ; ?>
										<tr>
    										<td width="55">
    											<img src="data:image/jpeg;base64, <?php echo $imagem?>" width="50" vspace="4" border="0" hspace="8">
											</td>
    										<td width="100%">
												<a href="anime_informacao?idanime=<?php echo $idanime;?>"><?php echo $nome;?></a>
												<?php if($_SESSION["nome"]==$procurar_utilizador){ ?><a class="icon-a" title="Editar anime" href="editar_animes?idanime=<?php echo $idanime;?>" onClick="window.open(this.href,'Editar Anime','resizable,height=300,width=300'); return false;">🖋</a><?php } ?>
											</td>
											<td>
												<?php echo $estado;?>
											</td>
											<td>
												<?php echo $episodios_vistos[$i] ?>/<?php echo $episodios;?>
											</td>
											<td>
												<?php echo $nota[$i];?>
											</td>
  										</tr>
  										<?php }$i++;}} ?>
									</table>
						</p></div>
					</div>
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