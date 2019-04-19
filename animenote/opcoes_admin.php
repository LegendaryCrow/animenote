<!DOCTYPE HTML>
<html>
	<head>
	<?php
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
    $con=new mysqli ("localhost","root","","animenote");
    mysqli_set_charset($con, 'utf8mb4');
	
	if(isset($_SESSION["tipo"])){	
		if($_SESSION["tipo"]!=1){
			header('Location:perfil');
			exit();
		}
	}else{
		header('Location:perfil');
		exit();
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
	?>
		<title>Opções do website - <?php echo $nome_website; ?></title>
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
		<script type="text/javascript">
		function toggleDiv(divId) {
			$("#"+divId).toggle();
		}
		</script>
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
						<div class="row">
		
							<!-- Sidebar -->
							<div id="sidebar" class="4u">
								<section>
									<header class="major">
										<br>
										<h1>📌 Inserir nova informação</h1>
									</header>
									<ul class="default">
										<li><a onclick="location.href='novo_anime'">Novo anime</a></li>
										<li><a onclick="location.href='nova_pessoa'">Nova pessoa</a></li>
										<li><a onclick="location.href='nova_personagem'">Novo personagem</a></li>
									</ul>
									<header class="major">
										<br>
										<h1>📝 <a href="javascript:toggleDiv('novosdados');">Novos dados ⬇️</a></h1>
									</header>
									<ul class="default" id="novosdados" style="display: none; margin: 0px;">
										<li><a href="javascript:toggleDiv('myContent2');">Novo tipo de anime</a></li>
										<form method="post" action="novos_dados_script?dado=1" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent2" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<input type = 'text' name='nome' required>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Adicionar"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent1');">Nova temporada</a></li>
										<form method="post" action="novos_dados_script?dado=2" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent1" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<input type = 'text' name='nome' required>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Adicionar"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent4');">Novo estudio</a></li>
										<form method="post" action="novos_dados_script?dado=3" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent4" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<input type = 'text' name='nome' required>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Adicionar"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent6');">Novo género</a></li>
										<form method="post" action="novos_dados_script?dado=4" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent6" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<input type = 'text' name='nome' required>
												</td>
											</tr>
											<tr>
												<td>
													Descrição:
												</td>
												<td>
													<textarea name='descricao'></textarea>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Adicionar"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent7');">Nova licenciador</a></li>
										<form method="post" action="novos_dados_script?dado=5" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent7" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<input type = 'text' name='nome' required>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Adicionar"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent9');">Novo produtor</a></li>
										<form method="post" action="novos_dados_script?dado=6" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent9" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<input type = 'text' name='nome' required>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Adicionar"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent8');">Nova língua</a></li>
										<form method="post" action="novos_dados_script?dado=7" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent8" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<input type = 'text' name='nome' required>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Adicionar"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent5');">Nova fonte de animes</a></li>
										<form method="post" action="novos_dados_script?dado=8" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent5" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<input type = 'text' name='nome' required>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Adicionar"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent3');">Nova classificação</a></li>
										<form method="post" action="novos_dados_script?dado=9" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent3" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<input type = 'text' name='nome' required>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Adicionar"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent');">Novo cargo</a></li>
										<form method="post" action="novos_dados_script?dado=10" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<input type = 'text' name='nome' required>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Adicionar"></center>
												</td>
											</tr>
										</table></div>
										</form>
									</ul>
									<header class="major">
										<br>
										<h1>🗑️ <a href="javascript:toggleDiv('removerdados');">Remover dados ⬇️</a></h1>
									</header>
									<ul class="default" id="removerdados" style="display: none; margin: 0px;">
										<li><a href="javascript:toggleDiv('myContent10');">Remover tipo de anime</a></li>
										<form method="post" action="eliminar_dados_script?dado=1" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent10" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<?php
													$sqlget = "SELECT * FROM `tipo` ORDER BY `tipo`.`nome` ASC ";
    												$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
													while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
														$id_tipo[] = $row['id_tipo'];
														$nome_tipo[] = $row['nome'];
    												}
													?>
													<select name="pessoa">
														<?php for($i=0; $i<count($id_tipo); $i++){ ?>
														<option value="<?php echo $id_tipo[$i]; ?>"><?php echo $nome_tipo[$i]; ?></option>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Remover"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent11');">Remover temporada</a></li>
										<form method="post" action="eliminar_dados_script?dado=2" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent11" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<?php
													$sqlget = "SELECT * FROM `temporada` ORDER BY `temporada`.`nome` ASC ";
    												$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
													while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
														$id_temporada[] = $row['id_temporada'];
														$nome_temporada[] = $row['nome'];
    												}
													?>
													<select name="pessoa">
														<?php for($i=0; $i<count($id_temporada); $i++){ ?>
														<option value="<?php echo $id_temporada[$i]; ?>"><?php echo $nome_temporada[$i]; ?></option>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Remover"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent12');">Remover estudio</a></li>
										<form method="post" action="eliminar_dados_script?dado=3" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent12" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<?php
													$sqlget = "SELECT * FROM `estudio` ORDER BY `estudio`.`nome` ASC ";
    												$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
													while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
														$id_estudio[] = $row['id_estudio'];
														$nome_estudio[] = $row['nome'];
    												}
													?>
													<select name="pessoa">
														<?php for($i=0; $i<count($id_estudio); $i++){ ?>
														<option value="<?php echo $id_estudio[$i]; ?>"><?php echo $nome_estudio[$i]; ?></option>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Remover"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent13');">Remover género</a></li>
										<form method="post" action="eliminar_dados_script?dado=4" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent13" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<?php
													$sqlget = "SELECT * FROM `generos` ORDER BY `generos`.`nome` ASC ";
    												$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
													while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
														$id_generos[] = $row['id_generos'];
														$nome_generos[] = $row['nome'];
    												}
													?>
													<select name="pessoa">
														<?php for($i=0; $i<count($id_generos); $i++){ ?>
														<option value="<?php echo $id_generos[$i]; ?>"><?php echo $nome_generos[$i]; ?></option>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Remover"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent14');">Remover licenciador</a></li>
										<form method="post" action="eliminar_dados_script?dado=5" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent14" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<?php
													$sqlget = "SELECT * FROM `licenciadores` ORDER BY `licenciadores`.`nome` ASC ";
    												$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
													while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
														$id_licenciadores[] = $row['id_licenciadores'];
														$nome_licenciadores[] = $row['nome'];
    												}
													?>
													<select name="pessoa">
														<?php for($i=0; $i<count($id_licenciadores); $i++){ ?>
														<option value="<?php echo $id_licenciadores[$i]; ?>"><?php echo $nome_licenciadores[$i]; ?></option>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Remover"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent15');">Remover produtor</a></li>
										<form method="post" action="eliminar_dados_script?dado=6" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent15" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<?php
													$sqlget = "SELECT * FROM `produtores` ORDER BY `produtores`.`nome` ASC ";
    												$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
													while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
														$id_produtores[] = $row['id_produtores'];
														$nome_produtores[] = $row['nome'];
    												}
													?>
													<select name="pessoa">
														<?php for($i=0; $i<count($id_produtores); $i++){ ?>
														<option value="<?php echo $id_produtores[$i]; ?>"><?php echo $nome_produtores[$i]; ?></option>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Remover"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent16');">Remover língua</a></li>
										<form method="post" action="eliminar_dados_script?dado=7" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent16" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<?php
													$sqlget = "SELECT * FROM `linguas` ORDER BY `linguas`.`lingua` ASC ";
    												$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
													while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
														$id_linguas[] = $row['id_linguas'];
														$lingua[] = $row['lingua'];
    												}
													?>
													<select name="pessoa">
														<?php for($i=0; $i<count($id_linguas); $i++){ ?>
														<option value="<?php echo $id_linguas[$i]; ?>"><?php echo $lingua[$i]; ?></option>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Remover"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent17');">Remover fonte de animes</a></li>
										<form method="post" action="eliminar_dados_script?dado=8" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent17" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<?php
													$sqlget = "SELECT * FROM `fonte` ORDER BY `fonte`.`nome` ASC ";
    												$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
													while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
														$id_fonte[] = $row['id_fonte'];
														$nome_fonte[] = $row['nome'];
    												}
													?>
													<select name="pessoa">
														<?php for($i=0; $i<count($id_fonte); $i++){ ?>
														<option value="<?php echo $id_fonte[$i]; ?>"><?php echo $nome_fonte[$i]; ?></option>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Remover"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent18');">Remover classificação</a></li>
										<form method="post" action="eliminar_dados_script?dado=9" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent18" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<?php
													$sqlget = "SELECT * FROM `classificacao` ORDER BY `classificacao`.`nome` ASC ";
    												$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
													while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
														$id_classificacao[] = $row['id_classificacao'];
														$nome_classificacao[] = $row['nome'];
    												}
													?>
													<select name="pessoa">
														<?php for($i=0; $i<count($id_classificacao); $i++){ ?>
														<option value="<?php echo $id_classificacao[$i]; ?>"><?php echo $nome_classificacao[$i]; ?></option>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Remover"></center>
												</td>
											</tr>
										</table></div>
										</form>
										<li><a href="javascript:toggleDiv('myContent19');">Remover cargo</a></li>
										<form method="post" action="eliminar_dados_script?dado=10" style="width: 100%;">
										<div class="dados" style="padding: 0px;"><table id="myContent19" style="display: none; margin: 0px;">
											<tr>
												<td>
													Nome:
												</td>
												<td>
													<?php
													$sqlget = "SELECT * FROM `cargo` ORDER BY `cargo`.`nome` ASC ";
    												$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
													while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
														$id_cargo[] = $row['id_cargo'];
														$nome_cargo[] = $row['nome'];
    												}
													?>
													<select name="pessoa">
														<?php for($i=0; $i<count($id_cargo); $i++){ ?>
														<option value="<?php echo $id_cargo[$i]; ?>"><?php echo $nome_cargo[$i]; ?></option>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr style="background: white">
												<td colspan="2">
												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Remover"></center>
												</td>
											</tr>
										</table></div>
										</form>
									</ul>
								</section>
							</div>
							
							<!-- Content -->
							<div id="content" class="8u skel-cell-important">
								<section>
									<form method="post" action="editar_configuracoes" style="width: 100%;">
									<header class="major">
									  <h2>Configurações</h2>
									</header>
									<div class="dados">
									<table>
										<tr>
											<td>Nome do website:</td>
											<td><input type = 'text' name='nome' value="<?php echo $nome_website; ?>" required></td>
										</tr>
										<tr>
											<td>Slogan do website:</td>
											<td><input type = 'text' name='slogan' value="<?php echo $slogan_website; ?>" required></td>
										</tr>
										<tr>
											<td>Copyright do website:</td>
											<td><input type='text' name='copyright' value="<?php echo htmlentities($copyright_website); ?>" required></td>
										</tr>
										<tr>
											<td>Quantidade de resultados no procurar:</td>
											<td><input type = 'number' name='procura' value="<?php echo $procurar_quantidade; ?>" required></td>
										</tr>
										<tr>
											<td>Favicon: <img src="data:image/jpeg;base64, <?php echo $favicon?>" id="blah" alt="favicon" width="16px"></td>
											<td><input type="hidden" name="MAX_FILE_SIZE" value="2000000"><input type="file" onchange="readURL(this);" name="myimage" accept="image/*" style="height: 100%;"></td>
										</tr>
										<tr>
											<td>Data de manutenção:<br><a href="manutencao" style="font-size: 10px;text-decoration: none;">(Efetuar manutenção)</a></td>
											<td><input type="text" name="manutencao" value="<?php echo $data_manutencao; ?>" required></td>
										</tr>
										<tr>
											<td colspan="2"><center><input type="submit" value="Atualizar"></center></td>
										</tr>
									</table>
									</div>
									</form>
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