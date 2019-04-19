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
	if(isset($_GET['utilizador'])){
		if($_GET['utilizador']==$_SESSION["nome"]){
			header('Location: login');
		}
	}else{
		header('Location: login');
	}
	$mensagem_para=$_GET['utilizador'];
	
	//Retirar informação da tabela utilizador
    $sqlget = "SELECT * FROM utilizador where nome='" . $mensagem_para . "' and ativacao like '0'";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: login'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$id_utilizador = $row['id_utilizador'];
    $nome = $row['nome'];
	if($nome==""){
		header('Location: perfil');
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
		<title>Mensagem - <?php echo $nome_website; ?></title>
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
					<div id="page" class="container dados page-mensagem">
       					<h2>Enviar uma mensagem para <a href="perfil?utilizador=<?php echo $mensagem_para; ?>"><?php echo $mensagem_para; ?></a></h2>
        				<form method="post" name="subMessage" style="display: inline;" action="enviar_mensagem_script?utilizador=<?php echo $mensagem_para; ?>">
        				<table width="100%" cellspacing="0" cellpadding="0" border="0">
        					<tr>
          						<td valign="top">
          							Para: <a href="perfil?utilizador=<?php echo $mensagem_para; ?>"><?php echo $mensagem_para; ?></a>
          							<div>Assunto:</div>
          							<input name="assunto" size="73" type="text">
          							<div>Mensagem:</div>
         	   						<textarea name="mensagem" class="textarea" rows="15" cols="90"></textarea>
         	   					</td>
        					</tr>
        					<tr>
        						<td>
        							<input style="font-size: 15px;" value="Enviar mensagem" type="submit">
      								<input onclick="location.href='mensagens'" style="font-size: 15px;" value="Cancelar" type="button">
        						</td>
        					</tr>
        				</table>
      					</form>
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