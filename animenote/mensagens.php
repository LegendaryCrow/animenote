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
	//Retirar informação da tabela mensagens
	$novas_mensagens=0;
	$sqlget = "SELECT * FROM `mensagens` WHERE id_utilizador2 like ".$_SESSION["id_utilizador"]." AND (checked like 0 or checked like 1) ORDER BY `data_hora` DESC ";
    $sqldata = mysqli_query($con,$sqlget) ;
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_mensagens[] = $row['id_mensagens'];
		$id_utilizador_mensagem[] = $row['id_utilizador1'];
		$assunto[] = $row['assunto'];
		$mensagem[] = $row['mensagem'];
		$data_hora_mensagem[] = $row['data_hora'];
		$checked[]=$row['checked'];
		if($row['checked']==0){
			$novas_mensagens++;
		}
    	$sqlget = "SELECT * FROM utilizador where id_utilizador='" . $row['id_utilizador1'] . "' and ativacao like '0'";
    	$sqldata1 = mysqli_query($con,$sqlget) or die (header('Location: login'));
		$row1 = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC);
		$utilizador_mensagem[] = $row1['nome'];
    }
	$sqlget = "SELECT * FROM `mensagens` WHERE id_utilizador1 like ".$_SESSION["id_utilizador"]." AND checked like 2 ORDER BY `data_hora` DESC ";
    $sqldata = mysqli_query($con,$sqlget) ;
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_mensagens2[] = $row['id_mensagens'];
		$id_utilizador_mensagem2[] = $row['id_utilizador2'];
		$assunto2[] = $row['assunto'];
		$mensagem2[] = $row['mensagem'];
		$data_hora_mensagem2[] = $row['data_hora'];
    	$sqlget = "SELECT * FROM utilizador where id_utilizador='" . $row['id_utilizador2'] . "' and ativacao like '0'";
    	$sqldata1 = mysqli_query($con,$sqlget) or die (header('Location: login'));
		$row1 = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC);
		$utilizador_mensagem2[] = $row1['nome'];
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
						<section><h2>Mensagens</h2>
						
						<div class="tab-button-wrapper">
						<ul>
							<li><a class="tab-button-first"
								id="tab-button1"
								href="javascript:void(0)"
								onclick="loadTab(1)">📥 Caixa de entrada</a></li>
							<li><a class="tab-button-hidden" 
								id="tab-button2"
								href="javascript:void(0)"
								onclick="loadTab(2)">📤 Enviadas</a></li>
						</ul>
					</div>
						<div class="tab-body-wrapper">
							<div class="tab-body" id="tab1">
								Tens <?php echo $novas_mensagens; ?> mensagens não lidas.<br>
								<?php if(isset($utilizador_mensagem)){ ?>
								<table>
										<tr class="mensagem">
											<th width="630px"></th>
											<th width="180px"></th>
											<th></th>
											<th></th>
										</tr>
										<?php for($i=0;$i<count($id_mensagens);$i++){ ?>
										<tr class="checked<?php echo $checked[$i]; ?>">
											<!--<td>
												<a href="apagar_mensagem_script?mensagem=<?php echo $id_mensagens[$i]; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
											</td>-->
											<td>
												<div class="cropmensage"><a href="perfil?utilizador=<?php echo $utilizador_mensagem[$i]; ?>"><?php echo $utilizador_mensagem[$i]; ?></a> ➡️
												<a href="ler_mensagem?mensagem=<?php echo $id_mensagens[$i]; ?>"><?php echo $assunto[$i]; ?></a> - <?php echo $mensagem[$i]; ?></div>
											</td>
											<td nowrap>
												<?php echo $data_hora_mensagem[$i]; ?>
											</td>
											<td nowrap>
												✉️ <a href="mensagem?utilizador=<?php echo $utilizador_mensagem[$i]; ?>">Responder</a>
											</td>
											<td nowrap>
												<a href="check_mensagem_script?mensagem=<?php echo $id_mensagens[$i]; ?>">
												<?php if($checked[$i]==0){ ?>
													<i class="fa fa-eye" aria-hidden="true" title="Marcar como lida"></i>
												<?php }elseif($checked[$i]==1){ ?>
													<i class="fa fa-eye-slash" aria-hidden="true" title="Marcar como não lida"></i>
												<?php } ?></a>
											</td>
										</tr>
										<?php } ?>
									</table>
									<?php } ?>
								</div>
								<div class="tab-body tab-body-hidden" id="tab2">
								Envias-te <?php if(isset($id_mensagens2)){ echo count($id_mensagens2); }else{ echo "0"; }?> mensagens.<br>
								<?php if(isset($utilizador_mensagem2)){ ?>
								<table class="listar">
										<tr class="mensagem">
											<th width="630px"></th>
											<th width="180px"></th>
											<th></th>
											<th></th>
										</tr>
										<?php for($i=0;$i<count($id_mensagens2);$i++){ ?>
										<tr class="checked1">
											<td>
												<div class="cropmensage"><a href="perfil?utilizador=<?php echo $utilizador_mensagem2[$i]; ?>"><?php echo $utilizador_mensagem2[$i]; ?></a> ⬅️
												<a href="ler_mensagem?mensagem=<?php echo $id_mensagens2[$i]; ?>"><?php echo $assunto2[$i]; ?></a> - <?php echo $mensagem2[$i]; ?></div>
											</td>
											<td nowrap>
												<?php echo $data_hora_mensagem2[$i]; ?>
											</td>
											<td nowrap>
												✉️ <a href="mensagem?utilizador=<?php echo $utilizador_mensagem2[$i]; ?>">Nova mensagem</a>
											</td>
										</tr>
										<?php } ?>
									</table>
									<?php } ?>
								</div>
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