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
	if(isset($_SESSION["nome"])) {
	}else{
		header('Location: login');
		exit();
	}
	//Retirar informação da tabela utilizador
    $sqlget = "SELECT * FROM utilizador where nome='" . $_SESSION["nome"] . "' and password='" . $_SESSION["password"] . "'";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: login'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$id_utilizador = $row['id_utilizador'];
    $nome = $row['nome'];
	$genero = $row['genero'] ;
	$data_nascimento = $row['data_nascimento'] ;
    $email = $row['email'] ;
	$localizacao = $row['localizacao'] ;
	$imagem = base64_encode( $row['imagem'] ) ;
	//Retirar informação da tabela personagens_favoritas
	$sqlget = "SELECT * FROM personagens_favoritas where id_utilizador='" . $_SESSION["id_utilizador"] . "'";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	if(isset($row['id_personagem1'])){
    	$id_personagem[] = $row['id_personagem1'];
	}
	if(isset($row['id_personagem2'])){
    	$id_personagem[] = $row['id_personagem2'];
	}
	if(isset($row['id_personagem3'])){
    	$id_personagem[] = $row['id_personagem3'];
	}
	if(isset($row['id_personagem4'])){
    	$id_personagem[] = $row['id_personagem4'];
	}
	if(isset($row['id_personagem5'])){
    	$id_personagem[] = $row['id_personagem5'];
	}
	//Retirar informação da tabela personagens (favoritos)
	$i=0;
	if(isset($id_personagem)){
		while(count($id_personagem)>$i){
			$sqlget = "SELECT * FROM personagens where id_personagens like $id_personagem[$i]";
			$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
			$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
			$id_personagens[] = $row['id_personagens'];
			$nome_personagem[] = $row['nome'];
			$imagem_personagem[] = base64_encode($row['imagem']);
			$i++;
		}
	}
	//Retirar informação da tabela animes_favoritos
	$sqlget = "SELECT * FROM animes_favoritos where id_utilizador='" . $_SESSION["id_utilizador"] . "'";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	if(isset($row['id_animes1'])){
    	$id_anime[] = $row['id_animes1'];
	}
	if(isset($row['id_animes2'])){
    	$id_anime[] = $row['id_animes2'];
	}
	if(isset($row['id_animes3'])){
    	$id_anime[] = $row['id_animes3'];
	}
	if(isset($row['id_animes4'])){
    	$id_anime[] = $row['id_animes4'];
	}
	if(isset($row['id_animes5'])){
    	$id_anime[] = $row['id_animes5'];
	}
	//Retirar informação da tabela animes (favoritos)
	$i=0;
	if(isset($id_anime)){
		while(count($id_anime)>$i){
			$sqlget = "SELECT * FROM animes where id_animes like $id_anime[$i]";
			$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
			$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
			$id_animes[] = $row['id_animes'];
			$nome_animes[] = $row['nome'];
			$imagem_animes[] = base64_encode($row['imagem']);
			$i++;
		}
	}
	//Retirar informação da tabela pessoas_favoritas
	$sqlget = "SELECT * FROM pessoas_favoritas where id_utilizador='" . $_SESSION["id_utilizador"] . "'";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	if(isset($row['id_pessoa1'])){
    	$id_pessoa[] = $row['id_pessoa1'];
	}
	if(isset($row['id_pessoa2'])){
    	$id_pessoa[] = $row['id_pessoa2'];
	}
	if(isset($row['id_pessoa3'])){
    	$id_pessoa[] = $row['id_pessoa3'];
	}
	if(isset($row['id_pessoa4'])){
    	$id_pessoa[] = $row['id_pessoa4'];
	}
	if(isset($row['id_pessoa5'])){
    	$id_pessoa[] = $row['id_pessoa5'];
	}
	//Retirar informação da tabela pessoas (favoritos)
	$i=0;
	if(isset($id_pessoa)){
		while(count($id_pessoa)>$i){
			$sqlget = "SELECT * FROM pessoa where id_pessoa like $id_pessoa[$i]";
			$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
			$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
			$id_pessoas[] = $row['id_pessoa'];
			$nome_pessoas[] = $row['nome'];
			$imagem_pessoas[] = base64_encode($row['imagem']);
			$i++;
		}
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
		<title>Editar Perfil - <?php echo $nome_website; ?></title>
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
			<link rel="stylesheet" href="css/jquery-ui.css" />
		</noscript>
		<link rel="stylesheet" href="css/jquery-ui.css">
  		<script src="js/jquery-1.12.4.js"></script>
  		<script src="js/jquery-ui.js"></script>
  		<script>
  			$( function() {
    			$( "#datepicker" ).datepicker();
  			} );
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
					<h2>Definições da conta</h2>
					<div class="tab-button-wrapper">
						<ul>
							<li><a class="tab-button-first"
								id="tab-button1"
								href="javascript:void(0)"
								onclick="loadTab(1)">Perfil</a></li>
							<li><a class="tab-button-hidden" 
								id="tab-button2"
								href="javascript:void(0)"
								onclick="loadTab(2)">Favoritos</a></li>
							<li><a class="tab-button-hidden" 
								id="tab-button3"
								href="javascript:void(0)"
								onclick="loadTab(3)">Imagem</a></li>
							<li><a class="tab-button-hidden tab-button-last" 
								id="tab-button4"
								href="javascript:void(0)"
								onclick="loadTab(4)">Conta</a></li>
						</ul>
					</div>
					<div class="tab-body-wrapper">
						<div class="tab-body" id="tab1"><p>
							<div class="dados">
							<div class="titulo">
							<h3>Alterar os meus detalhes:</h3>
								<form name="frmUser" method="post" action="detalhes_script"><table>
									<tr>
										<td>
											Género:
										</td>
										<td>
											<select name="genero">
												<option value="Desconhecido" <?php if(empty($genero)){ ?>selected="selected"<?php } ?>>Não especificado</option>
												<option value="Masculino" <?php if($genero=="Masculino"){ ?>selected="selected"<?php } ?>>Masculino</option>
												<option value="Feminino" <?php if($genero=="Feminino"){ ?>selected="selected"<?php } ?>>Feminino</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>
											Data de nascimento:
										</td>
										<td>
											<input type="text" name="data" id="datepicker" placeholder="Data de Nascimento" value="<?php echo $data_nascimento ?>">
										</td>
									</tr>
									<tr>
										<td>
											Localização:
										</td>
										<td>
											<input type="text" name="localizacao" placeholder="Localização" value="<?php echo $localizacao ?>">
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="submit" name="editar_informacao" value="Enviar">
										</td>
									</tr>
								</table></form>
							</div>
						</p></div></div>
						<div class="tab-body tab-body-hidden" id="tab2"><p>
						<div class="favoritos">
									<table>
										<tr><th>Animes</th><th>Personagens</th><th>Pessoas</th></tr>
										<tr><td>
											<?php if(isset($nome_animes[0])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_animes[0] ?>" alt="<?php echo $nome_animes[0];?>" class="ac" itemprop="image"><br><a href="anime_informacao?idanime=<?php echo $id_animes[0]; ?>">
											<?php echo $nome_animes[0]; ?></a><br>
											<div class="titulo"><form method="post" action="animes_favoritos_script?idanime=<?php echo $id_animes[0]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
											<td>
											<?php if(isset($nome_personagem[0])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_personagem[0] ?>" alt="<?php echo $nome_personagem[0];?>" class="ac" itemprop="image"><br><a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[0]; ?>">
											<?php echo $nome_personagem[0]; ?></a><br>
											<div class="titulo"><form method="post" action="personagens_favoritos_script?idpersonagem=<?php echo $id_personagens[0]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
											<td>
											<?php if(isset($nome_pessoas[0])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_pessoas[0] ?>" alt="<?php echo $nome_pessoas[0];?>" class="ac" itemprop="image"><br><a href="pessoa_informacao?idpessoa=<?php echo $id_pessoas[0]; ?>">
											<?php echo $nome_pessoas[0]; ?></a><br>
											<div class="titulo"><form method="post" action="pessoas_favoritos_script?id=<?php echo $id_pessoas[0]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
										</tr>
										<tr><td>
											<?php if(isset($nome_animes[1])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_animes[1] ?>" alt="<?php echo $nome_animes[1];?>" class="ac" itemprop="image"><br><a href="anime_informacao?idanime=<?php echo $id_animes[1]; ?>">
											<?php echo $nome_animes[1]; ?></a><br>
											<div class="titulo"><form method="post" action="animes_favoritos_script?idanime=<?php echo $id_animes[1]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_personagem[1])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_personagem[1] ?>" alt="<?php echo $nome_personagem[1];?>" class="ac" itemprop="image"><br><a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[1]; ?>">
											<?php echo $nome_personagem[1]; ?></a><br>
											<div class="titulo"><form method="post" action="personagens_favoritos_script?idpersonagem=<?php echo $id_personagens[1]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
											<td>
											<?php if(isset($nome_pessoas[1])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_pessoas[1] ?>" alt="<?php echo $nome_pessoas[1];?>" class="ac" itemprop="image"><br><a href="pessoa_informacao?idpessoa=<?php echo $id_pessoas[1]; ?>">
											<?php echo $nome_pessoas[1]; ?></a><br>
											<div class="titulo"><form method="post" action="pessoas_favoritos_script?id=<?php echo $id_pessoas[1]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
										</tr>
										<tr><td>
											<?php if(isset($nome_animes[2])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_animes[2] ?>" alt="<?php echo $nome_animes[2];?>" class="ac" itemprop="image"><br><a href="anime_informacao?idanime=<?php echo $id_animes[2]; ?>">
											<?php echo $nome_animes[2]; ?></a><br>
											<div class="titulo"><form method="post" action="animes_favoritos_script?idanime=<?php echo $id_animes[2]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_personagem[2])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_personagem[2] ?>" alt="<?php echo $nome_personagem[2];?>" class="ac" itemprop="image"><br><a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[2]; ?>">
											<?php echo $nome_personagem[2]; ?></a><br>
											<div class="titulo"><form method="post" action="personagens_favoritos_script?idpersonagem=<?php echo $id_personagens[2]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
											<td>
											<?php if(isset($nome_pessoas[2])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_pessoas[2] ?>" alt="<?php echo $nome_pessoas[2];?>" class="ac" itemprop="image"><br><a href="pessoa_informacao?idpessoa=<?php echo $id_pessoas[2]; ?>">
											<?php echo $nome_pessoas[2]; ?></a><br>
											<div class="titulo"><form method="post" action="pessoas_favoritos_script?id=<?php echo $id_pessoas[2]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
										</tr>
										<tr><td>
											<?php if(isset($nome_animes[3])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_animes[3] ?>" alt="<?php echo $nome_animes[3];?>" class="ac" itemprop="image"><br><a href="anime_informacao?idanime=<?php echo $id_animes[3]; ?>">
											<?php echo $nome_animes[3]; ?></a><br>
											<div class="titulo"><form method="post" action="animes_favoritos_script?idanime=<?php echo $id_animes[3]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_personagem[3])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_personagem[3] ?>" alt="<?php echo $nome_personagem[3];?>" class="ac" itemprop="image"><br><a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[3]; ?>">
											<?php echo $nome_personagem[3]; ?></a><br>
											<div class="titulo"><form method="post" action="personagens_favoritos_script?idpersonagem=<?php echo $id_personagens[3]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
											<td>
											<?php if(isset($nome_pessoas[3])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_pessoas[3] ?>" alt="<?php echo $nome_pessoas[3];?>" class="ac" itemprop="image"><br><a href="pessoa_informacao?idpessoa=<?php echo $id_pessoas[3]; ?>">
											<?php echo $nome_pessoas[3]; ?></a><br>
											<div class="titulo"><form method="post" action="pessoas_favoritos_script?id=<?php echo $id_pessoas[3]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
										</tr>
										<tr><td>
											<?php if(isset($nome_animes[4])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_animes[4] ?>" alt="<?php echo $nome_animes[4];?>" class="ac" itemprop="image"><br><a href="anime_informacao?idanime=<?php echo $id_animes[4]; ?>">
											<?php echo $nome_animes[4]; ?></a><br>
											<div class="titulo"><form method="post" action="animes_favoritos_script?idanime=<?php echo $id_animes[4]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_personagem[4])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_personagem[4] ?>" alt="<?php echo $nome_personagem[4];?>" class="ac" itemprop="image"><br><a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[4]; ?>">
											<?php echo $nome_personagem[4]; ?></a><br>
											<div class="titulo"><form method="post" action="personagens_favoritos_script?idpersonagem=<?php echo $id_personagens[4]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
											<td>
											<?php if(isset($nome_pessoas[4])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_pessoas[4] ?>" alt="<?php echo $nome_pessoas[4];?>" class="ac" itemprop="image"><br><a href="pessoa_informacao?idpessoa=<?php echo $id_pessoas[4]; ?>">
											<?php echo $nome_pessoas[4]; ?></a><br>
											<div class="titulo"><form method="post" action="pessoas_favoritos_script?id=<?php echo $id_pessoas[4]; ?>"><input type="submit" style="font-size: 8px; vertical-align: middle;" name="favoritos" value="Remover dos favoritos"></form></div>
											</td><?php } ?>
										</tr>
									</table>
						</div>
						</p></div>
						<div class="tab-body tab-body-hidden" id="tab3"><p>
							<table>
								<tr>
								<th>
									Imagem atual
								</th>
								<th>
									Upload de uma nova imagem
								</th>
								</tr>
								<tr>
								<td width="225">
								<?php if (empty($imagem)) { ?>
									<img src="images/user_noimage.png" alt="<?php echo $nome;?>" width="225" class="ac" itemprop="image">
								<?php }else{ ?>
									<img src="data:image/jpeg;base64, <?php echo $imagem ?>" alt="<?php echo $nome;?>" width="225" class="ac" itemprop="image">
								<?php } ?>
									<center>
									<form action="delete_image_script">
										<input type="submit" style="font-size: 8px;" name="remove_image" value="Remover">
									</form>
									</center>
								</td>
								<td>
									<form class="enviar" method="POST" action="upload_image_script" enctype="multipart/form-data">
										Selecione uma imagem no formato jpg, gif ou png do seu computador.
 										<p><input type="hidden" name="MAX_FILE_SIZE" value="2000000">
										<input type="file" name="myimage" accept="image/*"></p>
 										<input type="submit" name="submit_image" value="Enviar">
									</form>
								</td>
								</tr>
							</table>
						</p></div>
						<div class="tab-body tab-body-hidden" id="tab4"><p>
							<div class="dados">
							<h3>Alterar informação da conta:</h3>
							<form method="post" action="editar_dados_script" style="width: 100%;">
								<table>
									<tr>
										<td>
											Novo email:
										</td>
										<td>
											<input type="text" name="email" placeholder="Email">
										</td>
									</tr>
									<tr>
										<td>
											Novo nome de utilizador:
										</td>
										<td>
											<input type="text" name="nome" placeholder="Utilizador">
										</td>
									</tr>
									<tr>
										<td>
											Nova password:
										</td>
										<td>
											<input type="password" name="password" id="password" placeholder="Password">
										</td>
									</tr>
									<tr>
										<td>
											Confirmar nova password:
										</td>
										<td>
											<input type="password" name="password1" id="confirm_password" placeholder="Password">
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="submit" name="editar_informacao" value="Enviar">
										</td>
									</tr>
								</table>
								</form>
							</div>
							</p></div>
					</div>
					</div>
				<!-- /Page -->
	</div>
	<!-- Copyright -->
		<div id="copyright">
			<div class="container"> <span class="copyright"><?php echo $copyright_website; ?></span>
			</div>
		</div>

  		<script>
			var password = document.getElementById("password")
			, confirm_password = document.getElementById("confirm_password");
			
			function validatePassword(){
				if(password.value != confirm_password.value) {
					confirm_password.setCustomValidity("As passwords não correspondem.");
				} else {
					confirm_password.setCustomValidity('');
				}
			}
			
			password.onchange = validatePassword;
			confirm_password.onkeyup = validatePassword;
		</script>
	</body>
</html>