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
	if(isset($_GET['mensagem'])){
	}else{
		header('Location: login');
	}
	$id_mensagem=$_GET['mensagem'];
	
	//Retirar informação da tabela mensagens
    $sqlget = "SELECT * FROM mensagens where id_mensagens='$id_mensagem' and id_utilizador2='".$_SESSION["id_utilizador"]."' and (checked like '0' or checked like '1')";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: login'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$assunto = $row['assunto'];
    $mensagem = $row['mensagem'];
    $data_hora = $row['data_hora'];
    $checked = $row['checked'];
		$sqlget = "SELECT * FROM utilizador where id_utilizador='" . $row['id_utilizador1'] . "' and ativacao like '0'";
    	$sqldata1 = mysqli_query($con,$sqlget) or die (header('Location: login'));
		$row1 = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC);
		$utilizador_mensagem = $row1['nome'];
	if($assunto==""){
		$sqlget = "SELECT * FROM mensagens where id_mensagens='$id_mensagem' and id_utilizador1='".$_SESSION["id_utilizador"]."' and checked like '2'";
   		$sqldata = mysqli_query($con,$sqlget) or die (header('Location: login'));
		$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
		$assunto = $row['assunto'];
   		$mensagem = $row['mensagem'];
    	$data_hora = $row['data_hora'];
   		$checked = $row['checked'];
			$sqlget = "SELECT * FROM utilizador where id_utilizador='" . $row['id_utilizador2'] . "' and ativacao like '0'";
   			$sqldata1 = mysqli_query($con,$sqlget) or die (header('Location: login'));
			$row1 = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC);
			$utilizador_mensagem = $row1['nome'];
			if($assunto==""){
				header('Location: perfil');
			}
	}
	
	if($checked==0){
		$inserir="UPDATE `mensagens` SET `checked` = '1' WHERE `mensagens`.`id_mensagens` = $id_mensagem;";
		mysqli_query($con, $inserir) or die (header('Location: mensagens'));
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
       					<table style="margin: 0;">
       						<tr style="border-bottom: 1pt solid #ddd;">
       							<td width="100%">
       								<?php if($checked==2){ ?><h2>📤 Mensagem enviada para <a href="perfil?utilizador=<?php echo $utilizador_mensagem; ?>"><?php echo $utilizador_mensagem; ?></a></h2><?php }else{?><h2>📥 Mensagem recebida de <a href="perfil?utilizador=<?php echo $utilizador_mensagem; ?>"><?php echo $utilizador_mensagem; ?></a></h2><?php } ?>
       							</td>
       							<td style="text-align: right;" nowrap>
       								<?php echo $data_hora; ?>
       							</td>
       						</tr>
       						<tr>
       							<td colspan="2">
       								<b><?php echo $assunto; ?></b>
       							</td>
       						</tr>
       						<tr>
       							<td style="text-align: justify;" colspan="2">
       								<?php echo $mensagem; ?>
       							</td>
       						</tr>
       						<tr>
       							<td colspan="2" style="text-align: center;"><?php if($checked!=2){ ?>
       								<input style="font-size: 15px;" value="Responder" type="button" onClick="location.href='mensagem?utilizador=<?php echo $utilizador_mensagem; ?>'"><?php } ?>
        							<input style="font-size: 15px;" value="Minhas mensagem" type="button" onClick="location.href='mensagens'">
        							<input style="font-size: 15px;" value="Apagar" type="button" onClick="location.href='apagar_mensagem_script?mensagem=<?php echo $id_mensagem; ?>'">
       							</td>
       						</tr>
       					</table>
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