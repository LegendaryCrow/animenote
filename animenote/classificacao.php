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
		<title>Classificação Animes - <?php echo $nome_website; ?></title>
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
								<div class="titulo"><h2>Classificação Animes</h2></div>
							</header>
						
					<div class="tab-button-wrapper">
						<ul>
							<li><a class="tab-button-first"
								id="tab-button1"
								href="javascript:void(0)"
								onclick="loadTab(1)">Todos</a></li>
							<li><a class="tab-button-hidden" 
								id="tab-button2"
								href="javascript:void(0)"
								onclick="loadTab(2)">Completos</a></li>
							<li><a class="tab-button-hidden" 
								id="tab-button3"
								href="javascript:void(0)"
								onclick="loadTab(3)">Em lançamento</a></li>
							<li><a class="tab-button-hidden" 
								id="tab-button4"
								href="javascript:void(0)"
								onclick="loadTab(4)">Ainda não foram lançados</a></li>
						</ul>
					</div>
					<div class="tab-body-wrapper">
						<div class="tab-body" id="tab1"><p>
									<table class="listar">
										<tr>
											<th>#</th>
											<th colspan="2">Título</th>
											<th>Episódios</th>
											<th>Nota Média</th>
										</tr>
										<?php //Retirar informação da tabela animes
										$sqlget = "SELECT * FROM `animes`  ORDER BY `animes`.`top_anime` DESC ";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
    										$idanime[] = $row['id_animes'] ;
    										$nome[] = $row['nome'] ;
    										$episodios[] = $row['episodios'] ;
											$imagem[] = base64_encode( $row['imagem'] ) ;
    										$idestado[] = $row['id_estado'] ;
    										$nota_media[] = $row['nota_media'] ;
    									} ?>
   										<?php if(isset($idanime)){for($i=0; $i<count($idanime); $i++){ ?>
										<tr>
   											<td>
   												<h2><?php echo $i+1; ?></h2>
   											</td>
    										<td width="55">
    											<img src="data:image/jpeg;base64, <?php echo $imagem[$i]; ?>" width="50" vspace="4" border="0" hspace="8">
											</td>
    										<td width="100%">
												<a href="anime_informacao?idanime=<?php echo $idanime[$i];?>"><?php echo $nome[$i];?></a>
											</td>
											<td>
												<?php echo $episodios[$i] ?>
											</td>
											<td>
												<?php echo $nota_media[$i];?>
											</td>
  										</tr>
  										<?php }} ?>
									</table>
						</p></div>
						<div class="tab-body tab-body-hidden" id="tab2"><p>
									<table class="listar">
										<tr>
											<th>#</th>
											<th colspan="2">Título</th>
											<th>Episódios</th>
											<th>Nota Média</th>
										</tr>
										<?php //Retirar informação da tabela animes
										$sqlget = "SELECT * FROM `animes` where id_estado like 3  ORDER BY `animes`.`top_anime` DESC ";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
    										$idanime1[] = $row['id_animes'] ;
    										$nome1[] = $row['nome'] ;
    										$episodios1[] = $row['episodios'] ;
											$imagem1[] = base64_encode( $row['imagem'] ) ;
    										$idestado1[] = $row['id_estado'] ;
    										$nota_media1[] = $row['nota_media'] ;
    									} ?>
   										<?php if(isset($idanime1)){for($i=0; $i<count($idanime1); $i++){ ?>
										<tr>
   											<td>
   												<h2><?php echo $i+1; ?></h2>
   											</td>
    										<td width="55">
    											<img src="data:image/jpeg;base64, <?php echo $imagem1[$i]; ?>" width="50" vspace="4" border="0" hspace="8">
											</td>
    										<td width="100%">
												<a href="anime_informacao?idanime=<?php echo $idanime1[$i];?>"><?php echo $nome1[$i];?></a>
											</td>
											<td>
												<?php echo $episodios1[$i] ?>
											</td>
											<td>
												<?php echo $nota_media1[$i];?>
											</td>
  										</tr>
  										<?php }} ?>
									</table>
						</p></div>
						<div class="tab-body tab-body-hidden" id="tab3"><p>
									<table class="listar">
										<tr>
											<th>#</th>
											<th colspan="2">Título</th>
											<th>Episódios</th>
											<th>Nota Média</th>
										</tr>
										<?php //Retirar informação da tabela animes
										$sqlget = "SELECT * FROM `animes` where id_estado like 2  ORDER BY `animes`.`top_anime` DESC ";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
    										$idanime2[] = $row['id_animes'] ;
    										$nome2[] = $row['nome'] ;
    										$episodios2[] = $row['episodios'] ;
											$imagem2[] = base64_encode( $row['imagem'] ) ;
    										$idestado2[] = $row['id_estado'] ;
    										$nota_media2[] = $row['nota_media'] ;
    									} ?>
   										<?php if(isset($idanime2)){for($i=0; $i<count($idanime2); $i++){ ?>
										<tr>
   											<td>
   												<h2><?php echo $i+1; ?></h2>
   											</td>
    										<td width="55">
    											<img src="data:image/jpeg;base64, <?php echo $imagem2[$i]; ?>" width="50" vspace="4" border="0" hspace="8">
											</td>
    										<td width="100%">
												<a href="anime_informacao?idanime=<?php echo $idanime2[$i];?>"><?php echo $nome2[$i];?></a>
											</td>
											<td>
												<?php echo $episodios2[$i] ?>
											</td>
											<td>
												<?php echo $nota_media2[$i];?>
											</td>
  										</tr>
  										<?php }} ?>
									</table>
						</p></div>
						<div class="tab-body tab-body-hidden" id="tab4"><p>
									<table class="listar">
										<tr>
											<th>#</th>
											<th colspan="2">Título</th>
											<th>Episódios</th>
											<th>Nota Média</th>
										</tr>
										<?php //Retirar informação da tabela animes
										$sqlget = "SELECT * FROM `animes` where id_estado like 1  ORDER BY `animes`.`top_anime` DESC ";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
    										$idanime3[] = $row['id_animes'] ;
    										$nome3[] = $row['nome'] ;
    										$episodios3[] = $row['episodios'] ;
											$imagem3[] = base64_encode( $row['imagem'] ) ;
    										$idestado3[] = $row['id_estado'] ;
    										$nota_media3[] = $row['nota_media'] ;
    									} ?>
   										<?php if(isset($idanime3)){for($i=0; $i<count($idanime3); $i++){ ?>
										<tr>
   											<td>
   												<h2><?php echo $i+1; ?></h2>
   											</td>
    										<td width="55">
    											<img src="data:image/jpeg;base64, <?php echo $imagem3[$i]; ?>" width="50" vspace="4" border="0" hspace="8">
											</td>
    										<td width="100%">
												<a href="anime_informacao?idanime=<?php echo $idanime3[$i];?>"><?php echo $nome3[$i];?></a>
											</td>
											<td>
												<?php echo $episodios3[$i] ?>
											</td>
											<td>
												<?php echo $nota_media3[$i];?>
											</td>
  										</tr>
  										<?php }} ?>
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