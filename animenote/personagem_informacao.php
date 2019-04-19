<!DOCTYPE HTML>
<html><head>
	<?php
	session_start();
    header('Content-Type: text/html; charset=UTF-8');
    $con=new mysqli ("localhost","root","","animenote");
    mysqli_set_charset($con, 'utf8mb4');
	
    if($con->connect_error)
    {
        echo $con->connect_errno;
        die("Database Connection Failed");
    }
	$personagemid=$_GET['idpersonagem'];
	if($personagemid==''){
		header('Location: procurar');
	}
	$editar="";
	if(isset($_SESSION["tipo"])){
		$tipo_sessao=$_SESSION["tipo"];
		if($_SESSION["tipo"]==1){
			if(isset($_GET['edit'])){
				$editar=$_GET['edit'];
			}
		}
	}else{
		$tipo_sessao="";
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
	
	$favorito=0;
	//Retirar informação da tabela personagens_favoritas
	if(isset($_SESSION["id_utilizador"])){
	$sqlget = "SELECT * FROM personagens_favoritas where id_utilizador='" . $_SESSION["id_utilizador"] . "'";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	if(isset($row['id_personagem1'])){
		if($row['id_personagem1']==$personagemid){$favorito=1;}
	}
    if(isset($row['id_personagem2'])){
		if($row['id_personagem2']==$personagemid){$favorito=1;}
	}
    if(isset($row['id_personagem3'])){
		if($row['id_personagem3']==$personagemid){$favorito=1;}
	}
	if(isset($row['id_personagem4'])){
		if($row['id_personagem4']==$personagemid){$favorito=1;}
	}
	if(isset($row['id_personagem5'])){
		if($row['id_personagem5']==$personagemid){$favorito=1;}
	}
	}
	//Retirar informação da tabela personagens
    $sqlget = "SELECT * FROM personagens where id_personagens like $personagemid";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$id_personagens = $row['id_personagens'];
    $nome = $row['nome'];
	$biografia = $row['biografia'] ;
	$imagem = base64_encode( $row['imagem'] ) ;
	//Retirar informação da tabela atores
	$sqlget = "SELECT pessoa.*,linguas.lingua from personagens, ator_voz, linguas, pessoa where personagens.id_personagens like $personagemid && personagens.id_personagens like ator_voz.id_personagens && linguas.id_linguas like ator_voz.id_linguas && ator_voz.id_pessoa like pessoa.id_pessoa";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_pessoa[] = $row['id_pessoa'] ;
        $pessoa_nome[] = $row['nome'] ;
		$pessoa_lingua[] = $row['lingua'] ;
		$pessoa_imagem[] = base64_encode( $row['imagem'] ) ;
    }
	//Retirar informação da tabela animes
	$sqlget = "SELECT animes.*,animes_personagens.tipo from animes, animes_personagens, personagens where personagens.id_personagens like $personagemid && personagens.id_personagens like animes_personagens.id_personagens && animes_personagens.id_animes like animes.id_animes";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$idanimes[] = $row['id_animes'] ;
        $anime_nome[] = $row['nome'] ;
        $anime_tipo_personagem[] = $row['tipo'] ;
		$anime_imagem[] = base64_encode( $row['imagem'] ) ;
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
    	<script src="js/jquery.bpopup.min.js"></script>
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
					<?php if($editar=="true"){ ?>
					<form name="EditAnime" method="post" action="editar_personagem_script?idpersonagem=<?php echo $personagemid; ?>" enctype="multipart/form-data">
					<?php }elseif($editar=="atores"){ ?>
					<form name="EditAnime" method="post" action="editar_ator_script?idpersonagem=<?php echo $personagemid; ?>" enctype="multipart/form-data">
					<?php } ?>
					<div id="page" class="container">
						<div class="row">
		
							<!-- Sidebar -->
							<div id="sidebar" class="4u">
								<section>
										<img src="data:image/jpeg;base64, <?php echo $imagem?>" width="225" alt="<?php echo $nome;?>" class="ac" id="blah" itemprop="image">
								</section>
								<section><br>
										<?php if($editar=="atores"){ ?>
										<header class="major">
											<h1>Editar atores:</h1>
										</header>
										<?php }elseif($editar=="true"){ ?>
										<header class="major">
											<h1>Editar personagem:</h1>
										</header>
										<?php } ?>
										<?php if($editar!="true" && $editar!="atores"){ ?>
										<li><form method="post" action="personagens_favoritos_script?idpersonagem=<?php echo $personagemid; ?>"><input id="toggle-heart" type="checkbox" style="display: none" onChange="this.form.submit()" <?php if($favorito==0){ ?><?php }else{ ?>checked<?php } ?>><label for="toggle-heart"><?php if($favorito==0){ ?>❤<?php }else{ ?>💔<?php } ?></label><a class="item-html" href="#" onclick="$(this).closest('form').submit()"><?php if($favorito==0){ ?>Adicionar aos favoritos<?php }else{ ?>Remover dos favoritos<?php } ?></a></form></li>
										<?php }if(isset($tipo_sessao)){ if($tipo_sessao==1){ ?>
										<li>🔧 <a <?php if($editar!="true" && $editar!="atores"){ ?> onclick="location.href='personagem_informacao?idpersonagem=<?php echo $personagemid ?>&edit=true'"<?php }else{ ?>onclick="$(this).closest('form').submit()"<?php } ?>><?php if($editar!="true" && $editar!="atores"){ ?>Editar personagem<?php }else{ ?>Confirmar edição<?php } ?></a></li>
										<li>🗑️<a onclick="apagar()">Apagar personagem</a></li>
    									<script>
											function apagar() {
												var txt;
												var r = confirm("Tem a certeza que quer eliminar a personagem?\nDepois de apagado este não poderá ser recuperado.");
												if (r == true) {
													window.location.replace("apagar_personagem_script?idpersonagem=<?php echo $personagemid ?>");
												}
												document.getElementById("demo").innerHTML = txt;
											}
										</script>
										<?php if($editar=="true" || $editar=="atores"){ ?>
										<?php if($editar=="atores"){ ?>
										<li>📝 <a name="mouse.point" id="my-button">Adicionar novo ator</a></li>
										<?php } ?>
										<li>✖️ <a onclick="location.href='personagem_informacao?idpersonagem=<?php echo $personagemid ?>'">Cancelar</a></li>
										<?php }}} if(isset($tipo_sessao)){ if($editar!="atores" && $editar!="true" && $tipo_sessao==1){ ?>
											<li>📝 <a onclick="location.href='nova_personagem'">Nova personagem</a></li>
										<?php }} ?>
										<?php if($editar=="true"){ ?>
									<header class="major">
										<br>
										<h1>Informação</h1>
									</header>
									<ul class="default" name="edit">
										<li><b>Imagem: </b><input type="hidden" name="MAX_FILE_SIZE" value="2000000">
										<input type="file" onchange="readURL(this);" name="myimage" accept="image/*"></li>
									</ul>
									<?php } ?>
									<?php if(empty($anime_nome)) {}else{?>
									<header class="major">
										<br>
										<h1>Animes</h1>
									</header>
									<ul class="default">
										<?php $y=1;$w=0;
										while(count($anime_nome)>=$y){ ?>
										<li><img src="data:image/jpeg;base64, <?php echo $anime_imagem[$w]?>" alt="Shimura, Shinpachi" width="23" vspace="4" border="2" hspace="8" height="32"> <a href="anime_informacao?idanime=<?php echo $idanimes[$w];?>"><?php echo $anime_nome[$w];?></a></li>
										<?php $y++;$w++; } ?>
									</ul>
									<?php } ?>
								</section>
							</div>
							
							<!-- Content -->
							<div id="content" class="8u skel-cell-important">
								<section>
									<header class="major">
										<div class="titulo"><div class="dados h2-dados"><h2 id="titulo" <?php if($editar=="true"){ ?>class="editable"<?php } ?>><?php echo $nome;?></h2></div></div>
									</header>
									<h3>Biografia:</h3>
									<div class="dados h2-dados"><p id="biografia" <?php if($editar=="true"){ ?>class="editable1"<?php } ?>><?php echo $biografia;?></p></div>
									
									
									<?php if(empty($pessoa_nome)){}elseif(isset($tipo_sessao)){ if($tipo_sessao==1){ ?>
									<h3><?php if($editar!="atores"){if(isset($tipo_sessao)){if($tipo_sessao==1 && $editar!="true"){ ?><a name="mouse.point" onclick="location.href='personagem_informacao?idpersonagem=<?php echo $personagemid ?>&edit=atores'" title="Editar Atores de voz">🔧</a> Atores de voz:<?php }else{ ?> Atores de voz:<?php }}}else{ ?> Atores de voz:</h3>
									<?php }}else{ ?><h3>Atores de voz:<?php } ?></h3>
									<?php if($editar!="atores"){ ?>
									<table>
 										<?php $y=0;
										while(count($pessoa_nome)>$y){ ?>
  										<tr>
    										<td width="27" height="40">
    											<a><img src="data:image/jpeg;base64, <?php echo $pessoa_imagem[$y]?>" alt="Shimura, Shinpachi" width="23" vspace="4" border="2" hspace="8" height="32"></a>
											</td>
    										<td>
												<a href="pessoa_informacao?idpessoa=<?php echo $id_pessoa[$y];?>"><?php echo $pessoa_nome[$y];?></a>
											</td>
											<td style="text-align:right">
												<?php echo $pessoa_lingua[$y];?>
											</td>
  										</tr>
  										<?php $y++; } ?>
									</table>
									<?php }else{ ?>
									<table>
 										<?php $y=0;
										//Retirar da tabela linguas (todos)
										$sqlget = "SELECT * FROM `linguas` ORDER BY `linguas`.`lingua` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
											$id_lingua[] = $row['id_linguas'];
											$nome_lingua[] = $row['lingua'];
    									}
										while(count($pessoa_nome)>$y){ ?>
  										<tr>
    										<td width="27" height="40">
    											<a><img src="data:image/jpeg;base64, <?php echo $pessoa_imagem[$y]?>" alt="Shimura, Shinpachi" width="23" vspace="4" border="2" hspace="8" height="32"></a>
											</td>
    										<td>
												<a href="pessoa_informacao?idpessoa=<?php echo $id_pessoa[$y];?>"><?php echo $pessoa_nome[$y];?></a>
												<x onclick="location.href='remover_atores_script?idpersonagem=<?php echo $personagemid ?>&idpessoa=<?php echo $id_pessoa[$y]; ?>'">🗑️</x>
											</td>
											<td style="text-align:right">
												<select name="lingua<?php echo $id_pessoa[$y] ?>">
													<?php for($i=0; $i<count($id_lingua); $i++){ ?>
													<option value="<?php echo $id_lingua[$i]; ?>" <?php if($nome_lingua[$i]==$pessoa_lingua[$y]){ ?>selected="selected"<?php } ?>><?php echo $nome_lingua[$i]; ?></option>
													<?php } ?>
												</select>
											</td>
  										</tr>
  										<?php $y++; } ?>
									</table>
									<?php }} ?>
								</section>
							</div>
		
						</div>
					</div>
					</form>
				<!-- /Page -->
				
							<?php if(isset($_SESSION["nome"])){ ?>
            					<div id="element_to_pop_up">
									<div class="dados">
									<?php if($editar=="atores"){ ?>
										<form name="EditAnime" method="post" action="adicionar_ator_script?idpersonagem=<?php echo $personagemid; ?>"><h3>Adicionar atores ao anime:</h3>
   										<?php
										//Retirar da tabela linguas (todos)
										$sqlget = "SELECT * FROM `linguas` ORDER BY `linguas`.`lingua` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
											$id_lingua[] = $row['id_linguas'];
											$nome_lingua[] = $row['lingua'];
    									}
										//Retirar da tabela pessoas (todos)
										$sqlget = "SELECT * FROM `pessoa` ORDER BY `pessoa`.`nome` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
											$x=0;
											if(isset($id_pessoa)){for($i=0; $i<count($id_pessoa); $i++){
												if($id_pessoa[$i]==$row['id_pessoa']){
													$x=1;
												}
											}}
											if($x==0){
												$id_pessoas[] = $row['id_pessoa'];
											}
											$nome_pessoas[] = $row['nome'];
    									}
										if(isset($id_pessoas)){
										?>
										<table>
   										<tr>
    										<td nowrap>
    											Pessoa:
											</td>
    										<td>
												<select name="pessoaid">
													<?php for($i=0; $i<count($id_pessoas); $i++){ ?>
													<option value="<?php echo $id_pessoas[$i]; ?>"><?php echo $nome_pessoas[$i]; ?></option>
													<?php } ?>
												</select></li>
											</td>
  										</tr>
										<tr>
											<td>
												Língua:
											</td>
											<td>
												<select name="linguaid">
													<?php for($i=0; $i<count($id_lingua); $i++){ ?>
													<option value="<?php echo $id_lingua[$i]; ?>" <?php if(isset($pessoa_lingua)){if($id_lingua[$i]==$pessoa_lingua[$y]){ ?>selected="selected"<?php }} ?>><?php echo $nome_lingua[$i]; ?></option>
													<?php } ?>
												</select>
											</td>
										</tr>
  										<tr>
  											<td colspan="2">
  												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Adicionar"></center>
  											</td>
  										</tr></table>
  										<?php }else{ ?>
											Não existem novos atores para adicionar.
										<?php } ?>
										</form>
									<?php } ?>
									</div>
           							<a class="b-close">x<a/>
            					</div><?php } ?>
	</div>

	<!-- Copyright -->
		<div id="copyright">
			<div class="container"> <span class="copyright"><?php echo $copyright_website; ?></span>
			</div>
		</div>

	</body>
</html>