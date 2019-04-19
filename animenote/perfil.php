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
	if(isset($_SESSION["nome"]) || isset($_GET['utilizador'])){
	}else{
		header('Location: login');
	}
	if(empty($_SESSION["id_utilizador"])){
	}else{
		$id_utilizador_logado=$_SESSION["id_utilizador"];
	}
	
	if(empty($_GET['utilizador'])){
		if(isset($_SESSION["nome"])) {
			$procurar_utilizador=$_SESSION["nome"];
		}else{
			header('Location: login');
			exit();
		}
	}else{
		$procurar_utilizador=$_GET['utilizador'];
	}
	
	//Retirar informação da tabela utilizador
    $sqlget = "SELECT * FROM utilizador where nome='" . $procurar_utilizador . "' and ativacao like '0'";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: login'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$id_utilizador = $row['id_utilizador'];
    $nome = $row['nome'];
	$genero = $row['genero'] ;
    $email = $row['email'] ;
	$data_nascimento = $row['data_nascimento'] ;
	$localizacao = $row['localizacao'] ;
	$imagem = base64_encode( $row['imagem'] ) ;
	$notificacao = $row['notificacao'] ;
	if($nome==""){
		header('Location: perfil');
	}
	//Retirar informação da tabela personagens_favoritas
	$sqlget = "SELECT * FROM personagens_favoritas where id_utilizador='" . $id_utilizador . "'";
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
	$sqlget = "SELECT * FROM animes_favoritos where id_utilizador='" . $id_utilizador . "'";
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
	$sqlget = "SELECT * FROM pessoas_favoritas where id_utilizador='" . $id_utilizador . "'";
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
	//Retirar informação da tabela lista de animes (assistindo)
    $sqlget = "SELECT COUNT(lista_animes.id_estado) FROM lista_animes WHERE lista_animes.id_utilizador='" . $id_utilizador . "'  and lista_animes.id_estado='2'";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$assistindo = $row['COUNT(lista_animes.id_estado)'];
	//Retirar informação da tabela lista de animes (completos)
    $sqlget = "SELECT COUNT(lista_animes.id_estado) FROM lista_animes WHERE lista_animes.id_utilizador='" . $id_utilizador . "'  and lista_animes.id_estado='1'";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$completos = $row['COUNT(lista_animes.id_estado)'];
	//Retirar informação da tabela lista de animes (em espera)
    $sqlget = "SELECT COUNT(lista_animes.id_estado) FROM lista_animes WHERE lista_animes.id_utilizador='" . $id_utilizador . "'  and lista_animes.id_estado='3'";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$em_espera = $row['COUNT(lista_animes.id_estado)'];
	//Retirar informação da tabela lista de animes (desistiu)
    $sqlget = "SELECT COUNT(lista_animes.id_estado) FROM lista_animes WHERE lista_animes.id_utilizador='" . $id_utilizador . "'  and lista_animes.id_estado='4'";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$desistiu = $row['COUNT(lista_animes.id_estado)'];
	//Retirar informação da tabela lista de animes (planeia assistir)
    $sqlget = "SELECT COUNT(lista_animes.id_estado) FROM lista_animes WHERE lista_animes.id_utilizador='" . $id_utilizador . "'  and lista_animes.id_estado='5'";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$planeia_assistir = $row['COUNT(lista_animes.id_estado)'];
	//Retirar informação da tabela lista de animes (total)
    $sqlget = "SELECT COUNT(lista_animes.id_estado) FROM lista_animes WHERE lista_animes.id_utilizador='" . $id_utilizador . "'";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$total = $row['COUNT(lista_animes.id_estado)'];
	if(isset($id_utilizador_logado)){
		//Verificar se é amigo do utilizador
		$sqlget = "SELECT * FROM lista_amigos where id_utilizador1='$id_utilizador_logado' and id_utilizador2='$id_utilizador'";
    	$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
		$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
		if(isset($row['id_lista_amigos'])){
			$add_amigo="false";
			$confirmacao = $row['confirmacao'];
		}else{
			$add_amigo="true";
			$confirmacao="0";
		}
		//Verificar se enviou pedido de amizade
		$sqlget = "SELECT * FROM lista_amigos where id_utilizador1='$id_utilizador' and id_utilizador2='$id_utilizador_logado' and confirmacao='1'";
    	$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
		$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
		if(isset($row['id_lista_amigos'])){
			$aceitar_pedido="1";
		}else{
			$aceitar_pedido="0";
		}
	}
	//Retirar todos os id's dos amigos do utilizador
	$sqlget = "SELECT * FROM lista_amigos where id_utilizador1='$id_utilizador' and confirmacao='0'";
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
		$imagem_amigos[] = base64_encode( $row['imagem'] ) ;
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
	//Retirar ultimas atualizacoes
	$sqlget = "SELECT * FROM `historico_anime` WHERE `id_utilizador` LIKE $id_utilizador ORDER BY `data` DESC LIMIT 3";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_anime_update[] = $row['id_anime'] ;
		$data_update[] = date( 'd-m H:i', strtotime($row['data']) );
    }
	?>
		<title>Perfil <?php if($genero=="Masculino"){echo "do";}elseif($genero=="Feminino"){echo "da";}elseif($genero=="Desconhecido"){echo "do/a";} ?> <?php echo $nome ?> - <?php echo $nome_website; ?></title>
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
						<div class="row">
		
							<!-- Sidebar -->
							<div id="sidebar" class="4u">
								<section>
									<?php if (empty($imagem)) { ?>
										<img src="images/user_noimage.png" alt="<?php echo $nome;?>" width="225" class="ac" itemprop="image">
									<?php }else{ ?>
										<img src="data:image/jpeg;base64, <?php echo $imagem ?>" alt="<?php echo $nome;?>" width="225" class="ac" itemprop="image">
									<?php } ?>
								</section>
								<section><br>
										<?php if(isset($_SESSION["nome"])){ if($_SESSION["nome"]==$procurar_utilizador){ ?>
										<li>🔧 <a onclick="location.href='editar_perfil'" id="my-button">Editar informação</a></li>
										<?php }} ?>
										<li>📖 <a onclick="location.href='lista_animes<?php if(empty($_GET['utilizador'])){}else{ ?>?utilizador=<?php echo $_GET['utilizador']; } ?>'" id="my-button">Lista de animes</a></li>
										<?php if(isset($_SESSION["nome"])){ if($_SESSION["nome"]!=$procurar_utilizador){ ?>
										<li>👥 <a onclick="location.href='amigos_script?utilizador=<?php echo $procurar_utilizador; if($aceitar_pedido=="1"){ ?>&aceitar=1<?php } ?>'" id="my-button"><?php if($add_amigo=="true" && $aceitar_pedido=="0"){ ?>Adicionar aos amigos<?php }elseif($confirmacao=="0" && $aceitar_pedido=="0"){ ?>Remover dos amigos<?php }elseif($confirmacao=="1" && $aceitar_pedido=="0"){ ?>Cancelar pedido de amizade<?php }elseif($aceitar_pedido=="1"){ ?>Aceitar pedido de amizade<?php } ?></a></li>
										<li>📩 <a onclick="location.href='mensagem?utilizador=<?php echo $procurar_utilizador; ?>'" id="my-button">Enviar mensagem</a></li>
										<?php }} ?>
										<?php if(isset($_SESSION["nome"])){ if($_SESSION["nome"]==$procurar_utilizador){ ?>
										<li>📩 <a onclick="location.href='mensagens'" id="my-button">Mensagens</a></li>
										<li>👥 <a onclick="location.href='amigos'" id="my-button">Amigos</a></li>
										<?php }} ?>
										<?php if(isset($_SESSION["tipo"])){ if($_SESSION["tipo"]==1 && $_SESSION["nome"]!=$procurar_utilizador){ ?>
										<li>🗑️<a onclick="apagar()">Apagar utilizador</a></li>
    									<script>
											function apagar() {
												var txt;
												var r = confirm("Tem a certeza que quer eliminar o utilizador?\nDepois de removido este não poderá ser recuperado.");
												if (r == true) {
													window.location.replace("apagar_utilizador_script?idutilizador=<?php echo $id_utilizador ?>");
												}
												document.getElementById("demo").innerHTML = txt;
											}
										</script>
										<?php } if($_SESSION["tipo"]==1 && $_SESSION["nome"]==$procurar_utilizador){ ?>
										<li>⚒️ <a onclick="location.href='opcoes_admin'">Opções do website</a></li>
										<?php }} ?>
									<header class="major">
										<br>
										<h1>Informação</h1>
									</header>
									<ul class="default">
										<li><b>Género: </b><?php if($genero==""){echo "Não especificado";}else{echo $genero;}?></li>
										<li><b>Data de nascimento: </b><?php echo $data_nascimento;?></li>
										<li><b>E-mail: </b><?php echo $email;?></li>
										<li><b>Localização: </b><?php echo $localizacao;?></li>
									</ul>
									<?php if(isset($id_amigos)){ ?>
									<header class="major">
										<h1>Amigos</h1>
									</header>
									<ul class="default">
										<table class="amigos">
											<?php $x=0; for($i=0; $i<count($nome_amigos); $i++){ if($x==0){ ?>
											<tr>
												<?php } ?>
												<td>
												<?php if(empty($imagem_amigos[$i])) { ?>
													<img class="amigos-img" src="images/user_noimage.png" alt="<?php echo $nome_amigos[$i];?>" width="80">
												<?php }else{ ?>
													<img class="amigos-img" src="data:image/jpeg;base64, <?php echo $imagem_amigos[$i] ?>" alt="<?php echo $nome;?>" width="80">
												<?php } ?><br>
													<a href="?utilizador=<?php echo $nome_amigos[$i];?>"><?php echo $nome_amigos[$i]; $x++; ?></a>
												</td>
												<?php if($x==3){ $x=0; ?>
											</tr>
											<?php }} ?>
											<tr>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										</table>
									</ul>
									<?php } ?>
								</section>
							</div>
							
							<!-- Content -->
							<div id="content" class="8u skel-cell-important">
								<section>
									<header class="major">
									  <h2>Perfil <?php if($genero=="Masculino"){echo "do";}elseif($genero=="Feminino"){echo "da";}elseif($genero=="Desconhecido"){echo "do/a";} ?> <?php echo $nome;?></h2>
									</header>
									  <hr>
									  <div class="user-stats">
									  <div class="stats">
										<h3>Estatísticas de animes:</h3>
										<table>
											<tr>
												<td>Assistindo:</td>
												<td><?php echo $assistindo ?></td>
											</tr>
											<tr>
												<td>Completos:</td>
												<td><?php echo $completos ?></td>
											</tr>
											<tr>
												<td>Em espera:</td>
												<td><?php echo $em_espera ?></td>
											</tr>
											<tr>
												<td>Desistiu:</td>
												<td><?php echo $desistiu ?></td>
											</tr>
											<tr>
												<td>Planeia assistir:</td>
												<td><?php echo $planeia_assistir ?></td>
											</tr>
											<tr>
												<th>Total:</th>
												<th><b><?php echo $total ?></b></th>
											</tr>
										</table>
									</div>
									<div class="atualizacao">
										<?php if(isset($id_anime_update)){ ?>
										<h3>Últimas atualizações:</h3>
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
									</div>
									</div>
									<?php if(isset($nome_animes) && isset($nome_personagem) && isset($nome_pessoas)){ ?>
									<h3>Favoritos:</h3>
									<div class="favoritos">
									<table>
										<tr><th>Animes</th><th>Personagens</th><th>Pessoas</th></tr>
										<tr><td>
											<?php if(isset($nome_animes[0])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_animes[0] ?>" alt="<?php echo $nome_animes[0];?>" class="ac" itemprop="image"><br><a href="anime_informacao?idanime=<?php echo $id_animes[0]; ?>">
											<?php echo $nome_animes[0]; ?></a><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_personagem[0])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_personagem[0] ?>" alt="<?php echo $nome_personagem[0];?>" class="ac" itemprop="image"><br><a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[0]; ?>">
											<?php echo $nome_personagem[0]; ?></a><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_pessoas[0])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_pessoas[0] ?>" alt="<?php echo $nome_pessoas[0];?>" class="ac" itemprop="image"><br><a href="pessoa_informacao?idpessoa=<?php echo $id_pessoas[0]; ?>">
											<?php echo $nome_pessoas[0]; ?></a><?php } ?>
											</td>
										</tr>
										<tr><td>
											<?php if(isset($nome_animes[1])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_animes[1] ?>" alt="<?php echo $nome_animes[1];?>" class="ac" itemprop="image"><br><a href="anime_informacao?idanime=<?php echo $id_animes[1]; ?>">
											<?php echo $nome_animes[1]; ?></a><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_personagem[1])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_personagem[1] ?>" alt="<?php echo $nome_personagem[1];?>" class="ac" itemprop="image"><br><a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[1]; ?>">
											<?php echo $nome_personagem[1]; ?></a><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_pessoas[1])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_pessoas[1] ?>" alt="<?php echo $nome_pessoas[1];?>" class="ac" itemprop="image"><br><a href="pessoa_informacao?idpessoa=<?php echo $id_pessoas[1]; ?>">
											<?php echo $nome_pessoas[1]; ?></a><?php } ?>
											</td>
										</tr>
										<tr><td>
											<?php if(isset($nome_animes[2])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_animes[2] ?>" alt="<?php echo $nome_animes[2];?>" class="ac" itemprop="image"><br><a href="anime_informacao?idanime=<?php echo $id_animes[2]; ?>">
											<?php echo $nome_animes[2]; ?></a><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_personagem[2])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_personagem[2] ?>" alt="<?php echo $nome_personagem[2];?>" class="ac" itemprop="image"><br><a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[2]; ?>">
											<?php echo $nome_personagem[2]; ?></a><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_pessoas[2])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_pessoas[2] ?>" alt="<?php echo $nome_pessoas[2];?>" class="ac" itemprop="image"><br><a href="pessoa_informacao?idpessoa=<?php echo $id_pessoas[2]; ?>">
											<?php echo $nome_pessoas[2]; ?></a><?php } ?>
											</td>
										</tr>
										<tr><td>
											<?php if(isset($nome_animes[3])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_animes[3] ?>" alt="<?php echo $nome_animes[3];?>" class="ac" itemprop="image"><br><a href="anime_informacao?idanime=<?php echo $id_animes[3]; ?>">
											<?php echo $nome_animes[3]; ?></a><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_personagem[3])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_personagem[3] ?>" alt="<?php echo $nome_personagem[3];?>" class="ac" itemprop="image"><br><a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[3]; ?>">
											<?php echo $nome_personagem[3]; ?></a><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_pessoas[3])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_pessoas[3] ?>" alt="<?php echo $nome_pessoas[3];?>" class="ac" itemprop="image"><br><a href="pessoa_informacao?idpessoa=<?php echo $id_pessoas[3]; ?>">
											<?php echo $nome_pessoas[3]; ?></a><?php } ?>
											</td>
										</tr>
										<tr><td>
											<?php if(isset($nome_animes[4])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_animes[4] ?>" alt="<?php echo $nome_animes[4];?>" class="ac" itemprop="image"><br><a href="anime_informacao?idanime=<?php echo $id_animes[4]; ?>">
											<?php echo $nome_animes[4]; ?></a><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_personagem[4])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_personagem[4] ?>" alt="<?php echo $nome_personagem[4];?>" class="ac" itemprop="image"><br><a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[4]; ?>">
											<?php echo $nome_personagem[4]; ?></a><?php } ?>
											</td>
											<td>
											<?php if(isset($nome_pessoas[4])){ ?>
												<img src="data:image/jpeg;base64, <?php echo $imagem_pessoas[4] ?>" alt="<?php echo $nome_pessoas[4];?>" class="ac" itemprop="image"><br><a href="pessoa_informacao?idpessoa=<?php echo $id_pessoas[4]; ?>">
											<?php echo $nome_pessoas[4]; ?></a><?php } ?>
											</td>
										</tr>
									</table>
									</div>
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