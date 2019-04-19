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
		
	if(isset($_SESSION["id_utilizador"])) {
	$id_utilizador=$_SESSION["id_utilizador"];
	//Retirar ultimas atualizacoes
	$sqlget = "SELECT * FROM `historico_anime` WHERE `id_utilizador` LIKE $id_utilizador ORDER BY `data` DESC LIMIT 3";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_anime_update[] = $row['id_anime'] ;
		$data_update[] = date( 'd-m H:i', strtotime($row['data']) );
    }
	}
	?>
		<title>Principal - <?php echo $nome_website; ?></title>
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
					<center><header class="major">
						<h2>Anime Note</h2>
						<span class="byline">Procure, encontre, partilhe e crie a sua lista de animes.</span>
					</header></center><br>
						<div class="row">
						
							<!-- Content -->
							<div id="content" class="8u skel-cell-important">
								<section>
									<?php if(isset($id_anime_update)){ ?>
										<h3>As minhas últimas atualizações:</h3>
										<table>
											<?php for($i=0; $i<count($id_anime_update); $i++){
											$sqlget = "SELECT nome, episodios, imagem FROM animes where id_animes like ".$id_anime_update[$i]."";
    										$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
											$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
											$nome_anime = $row['nome'] ;
											$episodios_anime = $row['episodios'] ;
											$imagem_update = base64_encode( $row['imagem'] ) ;
											$sqlget = "SELECT id_estado, episodios_vistos, nota FROM lista_animes where id_animes like ".$id_anime_update[$i]." and id_utilizador like $id_utilizador";
    										$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
											$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
											$episodios_vistos = $row['episodios_vistos'] ;
											$nota = $row['nota'] ;
											$id_estado = $row['id_estado'] ;
											$sqlget = "SELECT nome FROM estado where id_estado like ".$id_estado."";
    										$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
											$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
											$nome_estado = $row['nome'] ;
											?>
											<tr class="checked1">
												<td rowspan="2" width="55">
													<?php if (empty($imagem_update)) { ?>
													<img src="images/user_noimage.png" width="50" vspace="4" border="0" hspace="8">
												<?php }else{ ?>
													<img src="data:image/jpeg;base64, <?php echo $imagem_update; ?>" width="50" vspace="4" border="0" hspace="8">
												<?php } ?>
												</td>
												<td><a href="anime_informacao?idanime=<?php echo $id_anime_update[$i]; ?>"><?php echo $nome_anime; ?></a></td>
												<td style="text-align: right" nowrap><?php echo $data_update[$i]; ?></td>
											</tr>
											<tr class="checked1">
												<td colspan="2"><?php echo $nome_estado; ?> <?php echo $episodios_vistos; ?>/<?php echo $episodios_anime; ?> - Nota <?php echo $nota; ?></td>
											</tr>
											<?php } ?>
										</table>
										<?php } ?>
									<p>Seja bem vindo ao Anime Note, todas as novidades do mundo dos animes estão á sua espera. Atualize a sua lista de animes, procure um novo anime para assistir e fale com os seus amigos sobre as novidades.</p>
									<p>Esperamos que se divirta enquanto explorar o fantastico mundo dos animes, qualquer duvida ou problema entre em contacto conosco por <a href="mailto:animenote.web@gmail.com">e-mail</a> para contactar um administrador.</p>
									<?php if(isset($_SESSION["id_utilizador"])){}else{ ?>
									<p><a href="login">Inicie sessão</a> para atualizar a sua lista de animes e ver as novidades.</p>
									<p> Ainda não tem conta? Registe-se <a href="registar">aqui</a>.</p>
									<?php } ?>
								</section>
							</div>
		
							<!-- Sidebar -->
							<div id="sidebar" class="4u">
								<section>
									<header class="major">
										<h1>Melhores animes em lançamento</h1>
									</header>
									<ul class="default">
										<?php //Retirar informação da tabela animes
										$sqlget = "SELECT * FROM `animes` where id_estado like 3  ORDER BY `animes`.`top_anime` DESC  LIMIT 5";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
    										$idanime[] = $row['id_animes'] ;
    										$nome[] = $row['nome'] ;
    										$episodios[] = $row['episodios'] ;
											$imagem[] = base64_encode( $row['imagem'] ) ;
    										$idestado[] = $row['id_estado'] ;
    										$nota_media[] = $row['nota_media'] ;
    										$total_utilizadores[] = $row['total_utilizadores'] ;
											$sqlget1 = "SELECT * FROM `tipo` where id_tipo like ".$row['id_tipo']."";
    										$sqldata1 = mysqli_query($con,$sqlget1) or die ('error getting database');
											$row1 = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC);
    										$tipo[] = $row1['nome'] ;
    									} ?>
    									<?php for($i=0; $i<count($idanime); $i++){ ?>
										<li>
   											<table style="margin: 0;"><tr>
    										<td width="75" rowspan="3">
    											<img src="data:image/jpeg;base64, <?php echo $imagem[$i]; ?>" width="70" vspace="4" border="0" hspace="8">
											</td>
    										<td width="100%">
												<h1><a href="anime_informacao?idanime=<?php echo $idanime[$i];?>"><?php echo $nome[$i];?></a></h1>
											</td>
											</tr>
											<tr style="background: white;">
											<td>
												<?php echo $tipo[$i] ?>, <?php echo $episodios[$i] ?> Eps, Nota <?php echo $nota_media[$i];?>
											</td>
											</tr>
											<tr>
											<td>
												<?php echo $total_utilizadores[$i];?> utilizadores
											</td>
  											</tr></table>
  										</li>
										<?php } ?>
									</ul>
									<?php if(isset($_SESSION["id_utilizador"])) { ?>
									<header class="major">
										<h1>Atualizações recentes de amigos</h1>
									</header>
									<ul class="default">
										<?php
										$sqlget = "SELECT historico_anime.* FROM lista_amigos, historico_anime WHERE lista_amigos.id_utilizador1 like ".$_SESSION["id_utilizador"]." AND lista_amigos.id_utilizador2 LIKE historico_anime.id_utilizador ORDER BY historico_anime.data DESC LIMIT 5";
    									$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
    										$idanime1[] = $row['id_anime'] ;
											$sqlget1 = "SELECT * FROM `animes` where id_animes like ".$row['id_anime']."";
											$sqldata1 = mysqli_query($con,$sqlget1) or die ('error getting database');
											$row1 = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC);
    										$nome1[] = $row1['nome'] ;
    										$episodios1[] = $row1['episodios'] ;
											$imagem1[] = base64_encode( $row1['imagem'] ) ;
											$sqlget2 = "SELECT * FROM `utilizador` where id_utilizador like ".$row['id_utilizador']."";
											$sqldata2 = mysqli_query($con,$sqlget2) or die ('error getting database');
											$row2 = mysqli_fetch_array($sqldata2, MYSQLI_ASSOC);
    										$nome2[] = $row2['nome'] ;
											$data1[] = $row['data'] ;
											$sqlget3 = "SELECT * FROM `lista_animes` where id_utilizador like ".$row['id_utilizador']." and id_animes like ".$row['id_anime']."";
											$sqldata3 = mysqli_query($con,$sqlget3) or die ('error getting database');
											$row3 = mysqli_fetch_array($sqldata3, MYSQLI_ASSOC);
    										$episodios_vistos1[] = $row3['episodios_vistos'] ;
    									}	
    									?>
    									<?php for($i=0; $i<count($idanime1); $i++){ ?>
										<li>
   											<table style="margin: 0;"><tr>
    										<td width="75" rowspan="3">
    											<img src="data:image/jpeg;base64, <?php echo $imagem1[$i]; ?>" width="70" vspace="4" border="0" hspace="8">
											</td>
    										<td width="100%">
												<h1><a href="anime_informacao?idanime=<?php echo $idanime1[$i];?>"><?php echo $nome1[$i];?></a></h1>
											</td>
											</tr>
											<tr style="background: white;">
											<td>
												Assistiu a <?php echo $episodios_vistos1[$i] ?> de <?php echo $episodios1[$i] ?>
											</td>
											</tr>
											<tr>
											<td>
												<?php echo $data1[$i];?> por <a href="perfil?utilizador=<?php echo $nome2[$i]; ?>"><?php echo $nome2[$i]; ?></a>
											</td>
  											</tr></table>
  										</li>
										<?php } ?>
									</ul>
									<?php } ?>
								</section>
							</div>
							
						</div>
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