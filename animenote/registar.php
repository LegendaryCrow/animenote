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
		
		if(isset($_SESSION["nome"])) {
			header('Location: perfil');
		}
		if (isset($_GET['erro'])){
			$erro=$_GET['erro'];
		}else{
			$erro="";
		}
		if (isset($_GET['sucesso'])){
			$sucesso=$_GET['sucesso'];
		}else{
			$sucesso="";
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
		<title>Registar - <?php echo $nome_website; ?></title>
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
					<div>
						<section>
							<div class="login">
								<form name="frmUser" method="post" action="registar_script">
								<center><img src="images/user.png" width="50" vspace="4" border="2" hspace="8"><p><h2>Registar</h2></p>
 								<?php if($erro=="0"){ ?> As passwords não correspondem. <?php } ?>
 								<?php if($erro=="1"){ ?> O nome de utilizador já se encontra em uso. <?php } ?>
 								<?php if($erro=="2"){ ?> O email já se encontra em uso. <?php } ?>
 								<?php if($sucesso=="1"){ ?> Registo efetuado com sucesso, clique no link enviado para o seu email para confirmar. <?php } ?>
 								<?php if($sucesso=="2"){ ?> Algo aconteceu... Contacte o administrador.<br><a href="mailto:animenote.web@gmail.com">animenote.web@gmail.com</a> <?php } ?>
  								<input type="text" name="utilizador" placeholder="Novo Utilizador" required>
  								<input type="password" name="password" placeholder="Nova Password" id="password" required>
  								<input type="password" name="password_confirm" placeholder="Confirmar Password" id="password_confirm" oninput="check(this)" required>
  								<script language='javascript' type='text/javascript'>
								function check(input) {
									if (input.value != document.getElementById('password').value) {
										input.setCustomValidity('Password Must be Matching.');
									} else {
										// input is valid -- reset the error message
										input.setCustomValidity('');
									}
								}
								</script>
 								<input type="email" name="email" placeholder="Email" required>
  								<input type="submit" name="submit" value="Registar">
								<p><h4>Já tem uma conta?</h4><a href="login" class="forgot">Iniciar sessão</a></p></center>
								</form>
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