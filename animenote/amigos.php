<!DOCTYPE HTML>
<html>
	<head>
	<?php
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
    $con=new mysqli ("localhost","root","","animenote");
    mysqli_set_charset($con, 'utf8mb4');
	
	if($con->connect_error){
        echo $con->connect_errno;
        die("Database Connection Failed");
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
	
	if(isset($_SESSION["nome"])){
	}else{
		header('Location: login');
		exit();
	}
	//Retirar informação da tabela mensagens (notificação)
	$novas_mensagens=0;
	$sqlget = "SELECT * FROM `mensagens` WHERE id_utilizador2 like ".$_SESSION["id_utilizador"]." AND checked like 0";
    $sqldata = mysqli_query($con,$sqlget) ;
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$novas_mensagens++;
    }
	//Retirar informação da tabela lista_amigos
	$pedidos_amizade=0;
	$sqlget = "SELECT * FROM `lista_amigos` WHERE id_utilizador2 like ".$_SESSION["id_utilizador"]." AND confirmacao like 1";
    $sqldata = mysqli_query($con,$sqlget) ;
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$novo_id_utilizador[] = $row['id_utilizador1'] ;
		$pedidos_amizade++;
		$sqlget = "SELECT * FROM `utilizador` WHERE id_utilizador like ".$row['id_utilizador1']." AND ativacao like 0";
    	$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
		$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
		$novo_nome[] = $row['nome'] ;
		$novo_genero[] = $row['genero'] ;
		$novo_imagem[] = base64_encode( $row['imagem'] ) ;
		//Verificar se enviou pedido de amizade
		$sqlget = "SELECT * FROM lista_amigos where id_utilizador1='".$row['id_utilizador']."' and id_utilizador2='".$_SESSION["id_utilizador"]."' and confirmacao='1'";
    	$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
		$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
		if(isset($row['id_lista_amigos'])){
			$aceitar_pedido="1";
		}else{
			$aceitar_pedido="0";
		}
	}
	//Retirar todos os id's dos amigos do utilizador
	$sqlget = "SELECT * FROM lista_amigos where id_utilizador1=".$_SESSION["id_utilizador"]." and confirmacao='0'";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_amigos[] = $row['id_utilizador2'] ;
    }
	if(isset($id_amigos)){
	//Retirar informações dos amigos do utilizador
	for($i=0; $i<count($id_amigos); $i++){
		$sqlget = "SELECT * FROM utilizador where id_utilizador='$id_amigos[$i]'";
    	$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
		$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
		$nome_amigos[] = $row['nome'] ;
		$localizacao_amigos[] = $row['localizacao'] ;
		$genero_amigos[] = $row['genero'] ;
		$imagem_amigos[] = base64_encode( $row['imagem'] ) ;
	}
	}
    ?>
		<title>Mensagens - <?php echo $nome_website; ?></title>
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
							<h2>Amigos</h2>
							<?php if(isset($novo_id_utilizador)){ for($i=0;$i<count($novo_id_utilizador);$i++){ ?>
								<p><h4>Novos pedidos de amizade:</h4>
								<table>
										<tr class="mensagem">
											<th width="55"></th>
											<th width="630px"></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
										
										<tr>
											<td>
												<?php if (empty($novo_imagem[$i])) { ?>
													<img src="images/user_noimage.png" alt="<?php echo $novo_nome[$i];?>" width="50" class="ac" itemprop="image">
												<?php }else{ ?>
													<img src="data:image/jpeg;base64, <?php echo $novo_imagem[$i] ?>" alt="<?php echo $novo_nome[$i];?>" width="50" class="ac" itemprop="image">
												<?php } ?>
											</td>
											<td>
												<div class="cropmensage"><a href="perfil?utilizador=<?php echo $novo_nome[$i]; ?>"><?php echo $novo_nome[$i]; ?></a>
											</td>
											<td>
												<?php echo $novo_genero[$i]; ?>
											</td>
											<td>
												<input onclick="location.href='amigos_script?utilizador=<?php echo $novo_nome[$i]; if($aceitar_pedido=="1"){ ?>&aceitar=1<?php } ?>'" style="font-size: 14px;" value="Aceitar" type="button">
											</td>
											<td>
												<a onclick="location.href='amigos_script?utilizador=<?php echo $novo_nome[$i];?>&aceitar=2'">Recusar</a>
											</td>
										</tr>
									</table>
								</p>
							<?php }} ?>
							<?php if(isset($id_amigos)){ for($i=0;$i<count($id_amigos);$i++){ ?>
								<p>
								<table>
										<tr class="mensagem">
											<th width="55">#</th>
											<th width="630px">Nome</th>
											<th>Localização</th>
											<th>Género</th>
										</tr>
										
										<tr>
											<td>
												<?php if (empty($imagem_amigos[$i])) { ?>
													<img src="images/user_noimage.png" alt="<?php echo $nome_amigos[$i];?>" width="50" class="ac" itemprop="image">
												<?php }else{ ?>
													<img src="data:image/jpeg;base64, <?php echo $imagem_amigos[$i] ?>" alt="<?php echo $nome_amigos[$i];?>" width="50" class="ac" itemprop="image">
												<?php } ?>
											</td>
											<td>
												<div class="cropmensage"><a href="perfil?utilizador=<?php echo $nome_amigos[$i]; ?>"><?php echo $nome_amigos[$i]; ?></a>
											</td>
											<td>
												<?php echo $localizacao_amigos[$i]; ?>
											</td>
											<td>
												<?php echo $genero_amigos[$i]; ?>
											</td>
										</tr>
									</table>
								</p>
							<?php }}if(empty($novo_id_utilizador) and empty($id_amigos)){?>	
								<br>Não tem amigos adicionados. Clique <a href="http://localhost/animenote/procurar?type=utilizadores">aqui</a> para encontrar os seus amigos.
							<?php } ?>
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