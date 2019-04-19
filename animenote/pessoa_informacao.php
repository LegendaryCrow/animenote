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
	$pessoaid=$_GET['idpessoa'];
	if($pessoaid==''){
		header('Location: procurar');
	}
	$editar="";
	if(isset($_SESSION["tipo"])){
	if($_SESSION["tipo"]==1){
		if(isset($_GET['edit'])){
			$editar=$_GET['edit'];
		}
	}
	}
		
	$favorito=0;
	//Retirar informação da tabela pessoas_favoritas
	if(isset($_SESSION["id_utilizador"])){
	$sqlget = "SELECT * FROM pessoas_favoritas where id_utilizador='" . $_SESSION["id_utilizador"] . "'";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	if(isset($row['id_pessoa1'])){
		if($row['id_pessoa1']==$pessoaid){$favorito=1;}
	}
    if(isset($row['id_pessoa2'])){
		if($row['id_pessoa2']==$pessoaid){$favorito=1;}
	}
    if(isset($row['id_pessoa3'])){
		if($row['id_pessoa3']==$pessoaid){$favorito=1;}
	}
	if(isset($row['id_pessoa4'])){
		if($row['id_pessoa4']==$pessoaid){$favorito=1;}
	}
	if(isset($row['id_pessoa5'])){
		if($row['id_pessoa5']==$pessoaid){$favorito=1;}
	}
	}
	//Retirar informação da tabela pessoas
    $sqlget = "SELECT * FROM pessoa where id_pessoa like $pessoaid";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$id_pessoa = $row['id_pessoa'];
    $nome = $row['nome'];
	$nome_proprio = $row['nome_proprio'];
	$sobrenome = $row['sobrenome'];
	$aniversario = $row['aniversario'] ;
    $website = $row['website'] ;
    $outros = $row['outros'] ;
	$imagem = base64_encode( $row['imagem'] ) ;
	//Retirar informação da tabela animes, personagens
	$sqlget = "SELECT animes.id_animes, animes.nome as 'nome_anime', animes.imagem as 'imagem_anime', personagens.id_personagens, personagens.nome as 'nome_personagem', personagens.imagem as 'imagem_personagem', animes_personagens.tipo FROM pessoa, ator_voz, personagens, animes_personagens, animes WHERE pessoa.id_pessoa like $pessoaid && pessoa.id_pessoa LIKE ator_voz.id_pessoa && ator_voz.id_personagens LIKE personagens.id_personagens && personagens.id_personagens LIKE animes_personagens.id_personagens && animes_personagens.id_animes LIKE animes.id_animes";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_animes[] = $row['id_animes'] ;
        $nome_anime[] = $row['nome_anime'] ;
		$imagem_anime[] = base64_encode( $row['imagem_anime'] ) ;
		$id_personagens[] = $row['id_personagens'] ;
		$nome_personagem[] = $row['nome_personagem'] ;
		$imagem_personagem[] = base64_encode( $row['imagem_personagem'] ) ;
		$tipo[] = $row['tipo'] ;
    }
	//Retirar informação da tabela staff
	$sqlget = "SELECT animes.id_animes, animes.nome, animes.imagem FROM pessoa, staff, animes WHERE pessoa.id_pessoa like $pessoaid && pessoa.id_pessoa LIKE staff.id_pessoa && staff.id_animes LIKE animes.id_animes";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_animes_staff[] = $row['id_animes'] ;
		$id_animes_staff_enviar = $row['id_animes'];
        $nome_anime_staff[] = $row['nome'] ;
		$imagem_anime_staff[] = base64_encode( $row['imagem'] ) ;
		$sqlget1 = "SELECT cargo.nome FROM staff, staff_cargo, cargo WHERE staff.id_animes LIKE $id_animes_staff_enviar && staff.id_pessoa like $pessoaid && staff.id_staff LIKE staff_cargo.id_staff && staff_cargo.id_cargo LIKE cargo.id_cargo";
    	$sqldata1 = mysqli_query($con,$sqlget1) or die (header('Location: procurar'));
		while ($row1 = mysqli_fetch_array($sqldata1, MYSQLI_ASSOC)) {
			$id_animes_posicao_staff[$id_animes_staff_enviar][] = $row1['nome'] ;
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
		<title><?php echo $nome;?> - <?php echo $nome_website; ?></title>
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
					<form name="EditAnime" method="post" action="editar_pessoa_script?idpessoa=<?php echo $pessoaid; ?>" enctype="multipart/form-data">
					<div id="page" class="container">
						<div class="row">
		
							<!-- Sidebar -->
							<div id="sidebar" class="4u">
								<section>
										<img src="data:image/jpeg;base64, <?php echo $imagem?>" width="225" alt="<?php echo $nome;?>" class="ac" itemprop="image">
								</section>
								<section><br><?php if($editar!="true"){ ?>
										<li><form method="post" action="pessoas_favoritos_script?idpessoa=<?php echo $pessoaid; ?>"><input id="toggle-heart" type="checkbox" style="display: none" onChange="this.form.submit()" <?php if($favorito==0){ ?><?php }else{ ?>checked<?php } ?>><label for="toggle-heart"><?php if($favorito==0){ ?>❤<?php }else{ ?>💔<?php } ?></label><a class="item-html" href="#" onclick="$(this).closest('form').submit()"><?php if($favorito==0){ ?>Adicionar aos favoritos<?php }else{ ?>Remover dos favoritos<?php } ?></a></form></li>
										<?php }if(isset($_SESSION["tipo"])){ if($_SESSION["tipo"]==1){ ?><li>🔧 <a <?php if($editar!="true"){ ?> onclick="location.href='pessoa_informacao?idpessoa=<?php echo $pessoaid ?>&edit=true'"<?php }else{ ?>onclick="$(this).closest('form').submit()"<?php } ?>><?php if($editar!="true"){ ?>Editar pessoa<?php }else{ ?>Confirmar edição<?php } ?></a></li>
										<li>🗑️<a onclick="apagar()">Apagar pessoa</a></li>
    									<script>
											function apagar() {
												var txt;
												var r = confirm("Tem a certeza que quer eliminar a pessoa?\nDepois de apagado este não poderá ser recuperado.");
												if (r == true) {
													window.location.replace("apagar_pessoa_script?idpessoa=<?php echo $pessoaid ?>");
												}
												document.getElementById("demo").innerHTML = txt;
											}
										</script>
										<?php if($editar=="true"){ ?>
										<li>✖️ <a onclick="location.href='pessoa_informacao?idpessoa=<?php echo $pessoaid ?>'">Cancelar</a></li>
										
										<?php }}} if(isset($_SESSION["tipo"])){ if($editar!="true" && $_SESSION["tipo"]==1){ ?>
											<li>📝 <a onclick="location.href='nova_pessoa'">Nova pessoa</a></li>
										<?php }} ?>
									<header class="major">
										<br>
										<h1>Informação</h1>
									</header>
									<ul class="default">
										<?php if($editar=="true"){ ?>
										<?php if($nome_proprio==""){ ?>
										<li class="dados width-auto"><b>Nome próprio: </b><x id="nome_proprio" class="editable" style="width: 220px;display: inline-block;height: 18px;"><?php echo $nome_proprio;?></x></li>
										<?php }else{ ?>
										<li class="dados width-auto"><b>Nome próprio: </b><x id="nome_proprio" class="editable"><?php echo $nome_proprio;?></x></li>
										<?php }if($sobrenome==""){ ?>
										<li class="dados width-auto"><b>Sobrenome: </b><x id="sobrenome" class="editable" style="width: 220px;display: inline-block;height: 18px;"><?php echo $sobrenome;?></x></li>
										<?php }else{ ?>
										<li class="dados width-auto"><b>Sobrenome: </b><x id="sobrenome" class="editable"><?php echo $sobrenome;?></x></li>
										<?php }if($aniversario==""){ ?>
										<li class="dados width-auto"><b>Aniversário: </b><x id="aniversario" class="editable" style="width: 220px;display: inline-block;height: 18px;"><?php echo $aniversario;?></x></li>
										<?php }else{ ?>
										<li class="dados width-auto"><b>Aniversário: </b><x id="aniversario" class="editable"><?php echo $aniversario;?></x></li>
										<?php }if($website==""){ ?>
										<li class="dados width-auto"><b>Website: </b><x id="website" class="editable" style="width: 220px;display: inline-block;height: 18px;"><?php echo $website;?></x></li>
										<?php }else{ ?>
										<li class="dados width-auto"><b>Website: </b><x id="website" class="editable"><?php echo $website;?></x></li>
										<?php }if($outros==""){ ?>
										<li class="dados width-auto"><b>Outros: </b><x id="outros" class="editable" style="width: 220px;display: inline-block;height: 18px;"><?php echo $outros;?></x></li>
										<?php }else{ ?>
										<li class="dados width-auto"><b>Outros: </b><x id="outros" class="editable"><?php echo $outros;?></x></li>
										<?php }}else{ ?>
										<li><b>Nome próprio: </b><?php echo $nome_proprio;?></li>
										<li><b>Sobrenome: </b><?php echo $sobrenome;?></li>
										<li><b>Aniversário: </b><?php echo $aniversario;?></li>
										<li><b>Website: </b><?php echo $website;?></li>
										<li><b>Outros: </b><?php echo $outros;?></li>
										<?php } ?>
									</ul>
								</section>
							</div>
							
							<!-- Content -->
							<div id="content" class="8u skel-cell-important">
								<section>
									<header class="major">
										<div class="titulo"><div class="dados h2-dados"><h2 id="titulo" <?php if($editar=="true"){ ?>class="editable"<?php } ?>><?php echo $nome;?></h2></div></div>
									</header>
									<?php if (empty($nome_anime)) { if($editar=="true"){ ?>
										<h3>Vozes dos personagens:</h3>
										<table>
											<?php if($editar=="true"){ ?>
											<tr><td colspan="3" style="background-color: white">🔧 <a onclick="location.href='pessoa_informacao?idpessoa=<?php echo $pessoaid ?>&edit=true'">Editar atores de voz do personagem</a></td></tr>
											<?php }?>
										</table>
									<?php }}else{?>
									<h3>Vozes dos personagens:</h3>
									<table>
 										<?php $y=1;$w=0;
										while(count($nome_anime)>=$y){ ?>
  										<tr>
    										<td width="27" height="40">
    											<a><img src="data:image/jpeg;base64, <?php echo $imagem_anime[$w]?>" alt="$nome_anime[$w]" width="23" vspace="4" border="2" hspace="8" height="32"></a>
											</td>
    										<td>
												<a href="anime_informacao?idanime=<?php echo $id_animes[$w];?>"><?php echo $nome_anime[$w];?></a>
											</td>
											<td style="text-align:right">
												<a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[$w];?>"><?php echo $nome_personagem[$w];?></a>
											</td>
											<td width="27" height="40">
    											<a><img src="data:image/jpeg;base64, <?php echo $imagem_personagem[$w]?>" alt="$nome_personagem[$w]" width="23" vspace="4" border="2" hspace="8" height="32"></a>
											</td>
  										</tr>
  										<?php $y++;$w++; }if($editar=="true"){ ?>
										<tr><td colspan="3" style="background-color: white">🔧 <a onclick="location.href='pessoa_informacao?idpessoa=<?php echo $pessoaid ?>&edit=true'">Editar atores de voz do personagem</a></td></tr>
										<?php } ?>
									</table>
									<?php } ?>
									<?php if (empty($nome_anime_staff)) { if($editar=="true"){ ?>
										<h3>Função nos animes:</h3>
										<table>
											<?php if($editar=="true"){ ?>
											<tr><td colspan="3" style="background-color: white">🔧 <a onclick="location.href='pessoa_informacao?idpessoa=<?php echo $pessoaid ?>&edit=true'">Editar funções nos animes</a></td></tr>
											<?php }?>
										</table>
									<?php }}else{ ?>
									<h3>Função nos animes:</h3>
									<table>
 										<?php $w=0; while(count($id_animes_staff)>$w){ ?>
  										<tr>
    										<td width="27" height="40">
    											<a><img src="data:image/jpeg;base64, <?php echo $imagem_anime_staff[$w]?>" alt="$nome_anime_staff[$w]" width="23" vspace="4" border="2" hspace="8" height="32"></a>
											</td>
    										<td>
												<a href="anime_informacao?idanime=<?php echo $id_animes_staff[$w];?>"><?php echo $nome_anime_staff[$w];?></a>
											</td>
											<td style="text-align:right">
												<?php if (empty($id_animes_posicao_staff)) {echo '';}
												else{ echo $id_animes_posicao_staff[$id_animes_staff[$w]][] = implode(', ', $id_animes_posicao_staff[$id_animes_staff[$w]]);}?>
											</td>
  										</tr>
  										<?php $w++; }if($editar=="true"){ ?>
										<tr><td colspan="3" style="background-color: white">🔧 <a onclick="location.href='pessoa_informacao?idpessoa=<?php echo $pessoaid ?>&edit=true'">Editar funções nos animes</a></td></tr>
										<?php } ?>
									</table>
									<?php } ?>
								</section>
							</div>
		
						</div>
					</div>
					</form>
				<!-- /Page -->
	</div>

	<!-- Copyright -->
		<div id="copyright">
			<div class="container"> <span class="copyright"><?php echo $copyright_website; ?></span>
			</div>
		</div>

	</body>
</html>