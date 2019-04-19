<!DOCTYPE HTML>
<html><head>
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
	
	if(isset($_SESSION["tipo"])){	
		if($_SESSION["tipo"]!=1){
			header('Location:perfil');
			exit();
		}
	}else{
		header('Location:perfil');
		exit();
	}
	
	//Retirar informação da tabela generos (todas)
	$sqlget = "SELECT * FROM `generos` ORDER BY `generos`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_generos[] = $row['id_generos'];
		$nome_generos[] = $row['nome'];
    }
	$total_generos=count($nome_generos);
	$colunas_generos=2;
	$linhas_generos=$total_generos/$colunas_generos;
	//Retirar informação da tabela produtores (todos)
	$sqlget = "SELECT * FROM `produtores` ORDER BY `produtores`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_produtores[] = $row['id_produtores'];
		$nome_produtores[] = $row['nome'];
    }
	$total_produtores=count($nome_produtores);
	$colunas_produtores=2;
	$linhas_produtores=$total_produtores/$colunas_produtores;
	//Retirar informação da tabela licenciadores (todos)
	$sqlget = "SELECT * FROM `licenciadores` ORDER BY `licenciadores`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_licenciadores[] = $row['id_licenciadores'];
		$nome_licenciadores[] = $row['nome'];
    }
	$total_licenciadores=count($nome_licenciadores);
	$colunas_licenciadores=2;
	$linhas_licenciadores=$total_licenciadores/$colunas_licenciadores;
	//Retirar informação da tabela estudio (todos)
	$sqlget = "SELECT * FROM `estudio` ORDER BY `estudio`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_estudio[] = $row['id_estudio'];
		$nome_estudio[] = $row['nome'];
    }
	$total_estudio=count($nome_estudio);
	$colunas_estudio=2;
	$linhas_estudio=$total_estudio/$colunas_estudio;
	//Retirar da tabela tipo (todos)
	$sqlget = "SELECT * FROM `tipo` ORDER BY `tipo`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_tipo1[] = $row['id_tipo'];
		$nome_tipo[] = $row['nome'];
    }
	//Retirar da tabela animes_estado (todos)
	$sqlget = "SELECT * FROM `animes_estado` ORDER BY `animes_estado`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_estado1[] = $row['id_estado'];
		$nome_estado[] = $row['nome'];
    }
	//Retirar da tabela temporada (todos)
	$sqlget = "SELECT * FROM `temporada` ORDER BY `temporada`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_temporada1[] = $row['id_temporada'];
		$nome_temporada[] = $row['nome'];
    }
	//Retirar da tabela fonte (todos)
	$sqlget = "SELECT * FROM `fonte` ORDER BY `fonte`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_fonte1[] = $row['id_fonte'];
		$nome_fonte[] = $row['nome'];
    }
	//Retirar da tabela classificacao (todos)
	$sqlget = "SELECT * FROM `classificacao` ORDER BY `classificacao`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_classificacao1[] = $row['id_classificacao'];
		$nome_classificacao[] = $row['nome'];
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
		<title>Novo anime - <?php echo $nome_website; ?></title>
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
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/x.x.x/jquery.min.js"></script>
    	<script src="js/jquery.bpopup.min.js"></script>
    	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
		<script type="text/javascript">
		function toggleDiv(divId) {
			$("#"+divId).toggle();
		}
    	</script>
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
					<form name="EditAnime" method="post" action="novo_anime_script" enctype="multipart/form-data">
					<div id="page" class="container">
						<div class="row">
						
							<!-- Sidebar -->
							<div id="sidebar" class="4u">
								<section>
										<img src="images/user_noimage.png" width="225" class="ac" id="blah" itemprop="image">
								</section>
								<section><br>
										<li>🔧 <a onclick="$(this).closest('form').submit()">Adicionar novo anime</a></li>
									<header class="major">
										<br>
										<h1>Informação</h1>
									</header>
									<ul class="default">
										<li><b>Imagem: </b><input type="hidden" name="MAX_FILE_SIZE" value="2000000">
										<input type="file" onchange="readURL(this);" name="myimage" accept="image/*"></li>
										<li><b>Tipo: </b>
										<select name="tipo">
											<?php for($i=0; $i<count($id_tipo1); $i++){ ?>
											<option value="<?php echo $id_tipo1[$i]; ?>"><?php echo $nome_tipo[$i]; ?></option>
											<?php } ?>
										</select>
										</li>
										<li><b>Episódios: </b><input type="number" name="episodios_anime" id="episodios_vistos" min="0" placeholder="Ep." style="width:65px;display:contents;" value="0"></li>
										<li><b>Estado: </b>
										<select name="estado_editar">
											<?php for($i=0; $i<count($id_estado1); $i++){ ?>
											<option value="<?php echo $id_estado1[$i]; ?>"><?php echo $nome_estado[$i]; ?></option>
											<?php } ?>
										</select>
										</li>
										<li class="dados width-auto"><b>Lançamento: </b><input type = 'text' name='lancamento' placeholder="De - Até" required>Exemplo.: 4 de Abr, 2006 até 25 de Mar, 2010</li>
										<li><b>Temporada: </b>
										<select name="temporada">
											<?php for($i=0; $i<count($id_temporada1); $i++){ ?>
											<option value="<?php echo $id_temporada1[$i]; ?>"><?php echo $nome_temporada[$i]; ?></option>
											<?php } ?>
										</select></li>
										<li><b>Produtores: </b><a href="javascript:toggleDiv('myContent');">Ver/Ocultar</a>
										<div class="generos"><table id="myContent" style="display: none; margin: 0px;">
										<?php $w=0; for ($i=0; $i<$linhas_produtores; $i++) { ?>
										<tr>
											<?php while($w<$total_produtores && $w<$colunas_produtores){ $name_produtores=0; ?>
											<td>
												<input type="checkbox" name="produtores[]" editar="change" value="<?php echo $id_produtores[$w]; ?>" id="produtores<?php echo $id_produtores[$w]; ?>" onclick="bs(this)"><label for="produtores<?php echo $id_produtores[$w]; ?>"><?php echo $nome_produtores[$w]; ?></label>
											</td>
											<?php $w++;} ?>
										</tr>
										<?php $colunas_produtores=$colunas_produtores+2;} ?>
										</table></div></li>
										<li><b>Licenciadores: </b><a href="javascript:toggleDiv('myContent3');">Ver/Ocultar</a>
										<div class="generos"><table id="myContent3" style="display: none; margin: 0px;">
										<?php $w=0; for ($i=0; $i<$linhas_licenciadores; $i++) { ?>
										<tr>
											<?php while($w<$total_licenciadores && $w<$colunas_licenciadores){ $name_licenciadores=0; ?>
											<td>
												<input type="checkbox" name="licenciadores[]" editar="change" value="<?php echo $id_licenciadores[$w]; ?>" id="licenciadores<?php echo $id_licenciadores[$w]; ?>" onclick="bs(this)"><label for="licenciadores<?php echo $id_licenciadores[$w]; ?>"><?php echo $nome_licenciadores[$w]; ?></label>
											</td>
											<?php $w++;} ?>
										</tr>
										<?php $colunas_licenciadores=$colunas_licenciadores+2;} ?>
										</table></div></li>
										<li><b>Estudio: </b><a href="javascript:toggleDiv('myContent4');">Ver/Ocultar</a>
										<div class="generos"><table id="myContent4" style="display: none; margin: 0px;">
										<?php $w=0; for ($i=0; $i<$linhas_estudio; $i++) { ?>
										<tr>
											<?php while($w<$total_estudio && $w<$colunas_estudio){ $name_estudio=0; ?>
											<td>
												<input type="checkbox" name="estudio[]" editar="change" value="<?php echo $id_estudio[$w]; ?>" id="estudio<?php echo $id_estudio[$w]; ?>" onclick="bs(this)"><label for="estudio<?php echo $id_estudio[$w]; ?>"><?php echo $nome_estudio[$w]; ?></label>
											</td>
											<?php $w++;} ?>
										</tr>
										<?php $colunas_estudio=$colunas_estudio+2;} ?>
										</table></div></li>
										<li><b>Fonte: </b>
										<select name="fonte">
											<?php for($i=0; $i<count($id_fonte1); $i++){ ?>
											<option value="<?php echo $id_fonte1[$i]; ?>"><?php echo $nome_fonte[$i]; ?></option>
											<?php } ?>
										</select></li>
										<li><b>Géneros: </b><a href="javascript:toggleDiv('myContent2');">Ver/Ocultar</a>
										<div class="generos"><table id="myContent2" style="display: none; margin: 0px;">
										<?php $w=0; for ($i=0; $i<$linhas_generos; $i++) { ?>
										<tr>
											<?php while($w<$total_generos && $w<$colunas_generos){ $name_genero=0; ?>
											<td>
												<input type="checkbox" name="generos[]" editar="change" value="<?php echo $id_generos[$w]; ?>" id="generos<?php echo $id_generos[$w]; ?>" onclick="bs(this)"><label for="generos<?php echo $id_generos[$w]; ?>"><?php echo $nome_generos[$w]; ?></label>
											</td>
											<?php $w++;} ?>
										</tr>
										<?php $colunas_generos=$colunas_generos+2;} ?>
										</table></div></li>
										<li class="dados width-auto"><b>Duração: </b><input type = 'text' name='duracao' placeholder="Tempo por episódio" required>Exemplo.: 24 min. por episódio</li>
										<li><b>Classificação: </b>
										<select name="classificacao">
											<?php for($i=0; $i<count($id_classificacao1); $i++){ ?>
											<option value="<?php echo $id_classificacao1[$i]; ?>"><?php echo $nome_classificacao[$i]; ?></option>
											<?php } ?>
										</select></li>
									</ul>
								</section>
							</div>
							
							<!-- Content -->
							<div id="content" class="8u skel-cell-important">
								<section>
									<header class="major" name="anime">
									<table>
										<tr>
											<td name="animenome" width="100%">
												<div class="titulo"><div class="dados h2-dados"><input type = 'text' name='titulo' placeholder="Título" required></div></div>
											</td>
										</tr>
									</table>
									</header>
									<h3>Sinopse:</h3>
									<div class="dados h2-dados"><textarea name='sinopse' placeholder="Sinopse..." required></textarea></div>
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