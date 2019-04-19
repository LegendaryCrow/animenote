<!DOCTYPE HTML>
<html><head>
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
	$animeid=$_GET['idanime'];
	if($animeid==''){
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
	if(($editar=="true" || $editar=="personagens" || $editar=="staff") && $_SESSION["tipo"]==0){
		header('Location:anime_informacao?idanime='.$animeid.'');
	}
	
	//Se existir sessao iniciada vai buscar informacao do anime/utilizador
	if(isset($_SESSION["nome"])){
		//Retirar informação da tabela lista_animes
		$sqlget = "SELECT * FROM `lista_animes` WHERE `id_utilizador`='" . $_SESSION["id_utilizador"] . "' and `id_animes`='" . $_GET["idanime"] . "'";
    	$sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
		$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
    	$add_idestado = $row['id_estado'] ;
    	$add_episodiosvistos = $row['episodios_vistos'] ;
    	$add_nota = $row['nota'] ;
	}
	$favorito=0;
	//Retirar informação da tabela animes_favoritos
	if(isset($_SESSION["id_utilizador"])){
	$sqlget = "SELECT * FROM animes_favoritos where id_utilizador='" . $_SESSION["id_utilizador"] . "'";
    $sqldata = mysqli_query($con,$sqlget) or die ('error getting database');
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	if(isset($row['id_animes1'])){
		if($row['id_animes1']==$animeid){$favorito=1;}
	}
    if(isset($row['id_animes2'])){
		if($row['id_animes2']==$animeid){$favorito=1;}
	}
    if(isset($row['id_animes3'])){
		if($row['id_animes3']==$animeid){$favorito=1;}
	}
	if(isset($row['id_animes4'])){
		if($row['id_animes4']==$animeid){$favorito=1;}
	}
	if(isset($row['id_animes5'])){
		if($row['id_animes5']==$animeid){$favorito=1;}
	}
	}
	//Retirar informação da tabela animes
    $sqlget = "SELECT * FROM animes where id_animes like $animeid";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$id_animes = $row['id_animes'];
    $nome = $row['nome'];
	$episodios = $row['episodios'] ;
    $lancamento = $row['lancamento'] ;
	$duracao = $row['duracao'] ;
	$sinopse = $row['sinopse'] ;
	$imagem = base64_encode( $row['imagem'] ) ;
	$id_classificacao = $row['id_classificacao'];
	$id_tipo = $row['id_tipo'];
	$id_estado = $row['id_estado'];
	$id_temporada = $row['id_temporada'];
	$id_fonte = $row['id_fonte'];
	$nota_media = $row['nota_media'];
	$total_notas_utilizadores = $row['total_utilizadores'];
	//Retirar informação da tabela classificacao
	$sqlget = "SELECT * FROM classificacao where id_classificacao like $id_classificacao";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$classificacao = $row['nome'];
	//Retirar informação da tabela generos
	$sqlget = "SELECT generos.nome from animes, animes_generos, generos where animes.id_animes like $id_animes && animes_generos.id_animes like animes.id_animes && animes_generos.id_generos like generos.id_generos order by generos.nome asc";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
        $generos[] = $row['nome'] ;
    }
	//Retirar informação da tabela generos (todas)
	$sqlget = "SELECT * FROM `generos` ORDER BY `generos`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_generos[] = $row['id_generos'];
		$nome_generos[] = $row['nome'];
    }
	$total_generos=count($nome_generos);
	$colunas_generos=2;
	$linhas_generos=$total_generos/$colunas_generos;
	//Retirar informação da tabela produtores
	$sqlget = "SELECT produtores.nome from animes, animes_produtores, produtores where animes.id_animes like $id_animes && animes_produtores.id_animes like animes.id_animes && animes_produtores.id_produtores like produtores.id_produtores order by produtores.nome asc";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
        $produtores[] = $row['nome'] ;
    }
	if(isset($produtores)){}else{$produtores[] ="";}
	//Retirar informação da tabela produtores (todos)
	$sqlget = "SELECT * FROM `produtores` ORDER BY `produtores`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_produtores[] = $row['id_produtores'];
		$nome_produtores[] = $row['nome'];
    }
	$total_produtores=count($nome_produtores);
	$colunas_produtores=2;
	$linhas_produtores=$total_produtores/$colunas_produtores;
	//Retirar informação da tabela licenciadores
	$sqlget = "SELECT licenciadores.nome from animes, animes_licenciadores, licenciadores where animes.id_animes like $id_animes && animes_licenciadores.id_animes like animes.id_animes && animes_licenciadores.id_licenciadores like licenciadores.id_licenciadores order by licenciadores.nome asc";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
        $licenciadores[] = $row['nome'] ;
    }
	if(isset($licenciadores)){}else{$licenciadores[] ="";}
	//Retirar informação da tabela licenciadores (todos)
	$sqlget = "SELECT * FROM `licenciadores` ORDER BY `licenciadores`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_licenciadores[] = $row['id_licenciadores'];
		$nome_licenciadores[] = $row['nome'];
    }
	$total_licenciadores=count($nome_licenciadores);
	$colunas_licenciadores=2;
	$linhas_licenciadores=$total_licenciadores/$colunas_licenciadores;
	//Retirar informação da tabela estudio
	$sqlget = "SELECT estudio.nome from animes, animes_estudio, estudio where animes.id_animes like $id_animes && animes_estudio.id_animes like animes.id_animes && animes_estudio.id_estudio like estudio.id_estudio order by estudio.nome asc";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
        $estudio[] = $row['nome'] ;
    }
	if(isset($estudio)){}else{$estudio[] ="";}
	//Retirar informação da tabela estudio (todos)
	$sqlget = "SELECT * FROM `estudio` ORDER BY `estudio`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_estudio[] = $row['id_estudio'];
		$nome_estudio[] = $row['nome'];
    }
	$total_estudio=count($nome_estudio);
	$colunas_estudio=2;
	$linhas_estudio=$total_estudio/$colunas_estudio;
	//Retirar informação da tabela personagens
	$sqlget = "SELECT * from animes, animes_personagens, personagens where animes.id_animes like $id_animes && animes_personagens.id_animes like animes.id_animes && animes_personagens.id_personagens like personagens.id_personagens";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_personagens[] = $row['id_personagens'] ;
        $personagens_nome[] = $row['nome'] ;
		$personagens_tipo[] = $row['tipo'] ;
		$personagens_imagem[] = base64_encode( $row['imagem'] ) ;
    }
	//Retirar informação da tabela staff
	$sqlget = "SELECT * from animes, staff, pessoa where animes.id_animes like $id_animes && staff.id_animes like animes.id_animes && staff.id_pessoa like pessoa.id_pessoa";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: sda'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_staff[] = $row['id_pessoa'] ;
		$id_staff_enviar = $row['id_pessoa'] ;
        $staff_nome[] = $row['nome'] ;
		$staff_imagem[] = base64_encode( $row['imagem'] ) ;
		$sqlget = "SELECT cargo.nome FROM staff, staff_cargo, cargo WHERE staff.id_animes LIKE $id_animes && staff.id_pessoa like $id_staff_enviar && staff.id_staff LIKE staff_cargo.id_staff && staff_cargo.id_cargo LIKE cargo.id_cargo ORDER BY `cargo`.`nome` ASC ";
    	$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
		while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
			$id_posicao_staff[$id_staff_enviar][] = $row['nome'] ;
		}
    }
	//Retirar informação da tabela tipo
	$sqlget = "SELECT * FROM tipo where id_tipo like $id_tipo";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$tipo = $row['nome'];
	//Retirar informação da tabela estado
	$sqlget = "SELECT * FROM animes_estado where id_estado like $id_estado";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$estado_anime = $row['nome'];
	//Retirar informação da tabela temporada
	$sqlget = "SELECT * FROM temporada where id_temporada like $id_temporada";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));    
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$temporada = $row['nome'];
	//Retirar informação da tabela fonte
	$sqlget = "SELECT * FROM fonte where id_fonte like $id_fonte";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
	$fonte = $row['nome'];
	//Retirar da tabela tipo (todos)
	$sqlget = "SELECT * FROM `cargo` ORDER BY `cargo`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_cargo[] = $row['id_cargo'];
		$nome_cargo[] = $row['nome'];
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
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/x.x.x/jquery.min.js"></script>
    	<script src="js/jquery.bpopup.min.js"></script>
    	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
		<script type="text/javascript">
		function toggleDiv(divId) {
			$("#"+divId).toggle();
		}
    	</script>
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
					<form name="EditAnime" method="post" action="editar_anime_script?idanime=<?php echo $animeid; ?>" enctype="multipart/form-data">
					<?php }elseif($editar=="staff"){ ?>
					<form name="EditAnime" method="post" action="editar_staff_script?idanime=<?php echo $animeid; ?>" enctype="multipart/form-data">
					<?php }elseif($editar=="personagens"){ ?>
					<form name="EditAnime" method="post" action="editar_personagens_script.php?idanime=<?php echo $animeid; ?>" enctype="multipart/form-data">
					<?php } ?>
					<div id="page" class="container">
						<div class="row">
							<!-- Sidebar -->
							<div id="sidebar" class="4u">
								<section>
										<img src="data:image/jpeg;base64, <?php echo $imagem?>" width="225" alt="<?php echo $nome;?>" class="ac" id="blah" itemprop="image">
								</section>
								<section><br>
										<?php if($editar!="personagens" && $editar!="staff"){if($editar!="true"){ ?>
										<li>🖋 <a <?php if(empty($_SESSION["nome"])){ ?> onclick="location.href='login'" <?php }else{ ?> id="my-button" <?php } ?>>Adicionar a lista</a></li>
										<li><form method="post" action="animes_favoritos_script?idanime=<?php echo $animeid; ?>"><input id="toggle-heart" type="checkbox" style="display: none" onChange="this.form.submit()" <?php if($favorito==0){ ?><?php }else{ ?>checked<?php } ?>><label for="toggle-heart"><?php if($favorito==0){ ?>❤️ <?php }else{ ?>💔 <?php } ?></label><a class="item-html" href="#" onclick="$(this).closest('form').submit()"><?php if($favorito==0){ ?>Adicionar aos favoritos<?php }else{ ?>Remover dos favoritos<?php } ?></a></form></li>
										<?php }if($editar=="true"){
										//Retirar da tabela tipo (todos)
										$sqlget = "SELECT * FROM `tipo` ORDER BY `tipo`.`nome` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
											$id_tipo1[] = $row['id_tipo'];
											$nome_tipo[] = $row['nome'];
    									}
										//Retirar da tabela animes_estado (todos)
										$sqlget = "SELECT * FROM `animes_estado` ORDER BY `animes_estado`.`nome` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
											$id_estado1[] = $row['id_estado'];
											$nome_estado[] = $row['nome'];
    									}
										//Retirar da tabela temporada (todos)
										$sqlget = "SELECT * FROM `temporada` ORDER BY `temporada`.`nome` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
											$id_temporada1[] = $row['id_temporada'];
											$nome_temporada[] = $row['nome'];
    									}
										//Retirar da tabela fonte (todos)
										$sqlget = "SELECT * FROM `fonte` ORDER BY `fonte`.`nome` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
											$id_fonte1[] = $row['id_fonte'];
											$nome_fonte[] = $row['nome'];
    									}
										//Retirar da tabela classificacao (todos)
										$sqlget = "SELECT * FROM `classificacao` ORDER BY `classificacao`.`nome` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
											$id_classificacao1[] = $row['id_classificacao'];
											$nome_classificacao[] = $row['nome'];
    									}}if(isset($_SESSION["tipo"])){if($_SESSION["tipo"]==1){ ?>
    									<li>🔧 <a <?php if($editar!="true"){ ?> onclick="location.href='anime_informacao?idanime=<?php echo $id_animes ?>&edit=true'"<?php }else{ ?>onclick="$(this).closest('form').submit()"<?php } ?>><?php if($editar!="true"){ ?>Editar anime<?php }else{ ?>Confirmar edição<?php } ?></a></li>
    									<li>🗑️<a onclick="apagar()">Apagar anime</a></li>
    									<script>
											function apagar() {
												var txt;
												var r = confirm("Tem a certeza que quer eliminar o anime?\nDepois de apagado este não poderá ser recuperado.");
												if (r == true) {
													window.location.replace("apagar_animes_script?idanime=<?php echo $animeid ?>");
												}
												document.getElementById("demo").innerHTML = txt;
											}
										</script>
										<?php }}if($editar=="true"){ ?>
										<li>✖️ <a onclick="location.href='anime_informacao?idanime=<?php echo $id_animes ?>'">Cancelar</a></li>
										<?php }}elseif($editar=="staff"){ ?>
										<header class="major">
											<br>
											<h1>Editar Equipa</h1>
										</header>
										<li>🔧 <a onclick="$(this).closest('form').submit()">Confirmar edição</a></li>
										<li>📝 <a name="mouse.point" id="my-button">Adicionar novo membro</a></li>
										<li>✖️ <a onclick="location.href='anime_informacao?idanime=<?php echo $id_animes ?>'">Cancelar</a></li>
										<?php }elseif($editar=="personagens"){ ?>
										<header class="major">
											<br>
											<h1>Editar Personagens</h1>
										</header>
										<li>🔧 <a onclick="$(this).closest('form').submit()">Confirmar edição</a></li>
										<li>📝 <a name="mouse.point" id="my-button">Adicionar novo personagem</a></li>
										<li>✖️ <a onclick="location.href='anime_informacao?idanime=<?php echo $id_animes ?>'">Cancelar</a></li>
										<?php } if(isset($tipo_sessao)){ if($editar!="personagens" && $editar!="staff" && $editar!="true" && $tipo_sessao==1){ ?>
											<li>📝 <a onclick="location.href='adicionar_anime'">Novo anime</a></li>
										<?php }} ?>
									<header class="major">
										<br>
										<h1>Informação</h1>
									</header>
									<ul class="default">
										<?php if($editar=="true"){ ?>
										<li><b>Imagem: </b><input type="hidden" name="MAX_FILE_SIZE" value="2000000">
										<input type="file" onchange="readURL(this);" name="myimage" accept="image/*"></li>
										<li><b>Tipo: </b>
										<select name="tipo">
											<?php for($i=0; $i<count($id_tipo1); $i++){ ?>
											<option value="<?php echo $id_tipo1[$i]; ?>" <?php if($id_tipo1[$i]==$id_tipo){ ?>selected="selected"<?php } ?>><?php echo $nome_tipo[$i]; ?></option>
											<?php } ?>
										</select>
										</li>
										<li><b>Episódios: </b><input type="number" name="episodios_anime" id="episodios_vistos" min="0" placeholder="Ep." style="width:65px;display:contents;" value="<?php echo $episodios ?>"></li>
										<li><b>Estado: </b>
										<select name="estado_editar">
											<?php for($i=0; $i<count($id_estado1); $i++){ ?>
											<option value="<?php echo $id_estado1[$i]; ?>" <?php if($id_estado1[$i]==$id_estado){ ?>selected="selected"<?php } ?>><?php echo $nome_estado[$i]; ?></option>
											<?php } ?>
										</select>
										</li>
										<li class="dados width-auto"><b>Lançamento: </b><x id="lancamento" class="editable"><?php echo $lancamento;?></x></li>
										<li><b>Temporada: </b>
										<select name="temporada">
											<?php for($i=0; $i<count($id_temporada1); $i++){ ?>
											<option value="<?php echo $id_temporada1[$i]; ?>" <?php if($id_temporada1[$i]==$id_temporada){ ?>selected="selected"<?php } ?>><?php echo $nome_temporada[$i]; ?></option>
											<?php } ?>
										</select></li>
										<li><b>Produtores: </b><a href="javascript:toggleDiv('myContent');">Ver/Ocultar</a>
										<div class="generos"><table id="myContent" style="display: none; margin: 0px;">
										<?php $w=0; for ($i=0; $i<$linhas_produtores; $i++) { ?>
										<tr>
											<?php while($w<$total_produtores && $w<$colunas_produtores){ $name_produtores=0; ?>
											<td>
												<input type="checkbox" name="produtores[]" <?php for($x=0; $x<count($produtores); $x++){ if($nome_produtores[$w]==$produtores[$x]){ ?> checked="true"<?php $name_produtores=1;}} ?> editar="change" value="<?php echo $id_produtores[$w]; ?>" id="produtores<?php echo $id_produtores[$w]; ?>" onclick="bs(this)"><label for="produtores<?php echo $id_produtores[$w]; ?>"><?php echo $nome_produtores[$w]; ?></label>
											</td>
											<?php $w++;} ?>
										</tr>
										<?php $colunas_produtores=$colunas_produtores+2;} ?>
										</table></div></li>
										<li><b>Licenciadores: </b><a href="javascript:toggleDiv('myContent3');">Ver/Ocultar</a>
										<div class="generos"><table id="myContent3" style="display: none; margin: 0px;">
										<?php $w=0; for ($i=0; $i<$linhas_licenciadores; $i++) { ?>
										<tr>
											<?php while($w<$total_licenciadores && $w<$colunas_licenciadores){ $name_licenciadores=0; ?>
											<td>
												<input type="checkbox" name="licenciadores[]" <?php for($x=0; $x<count($licenciadores); $x++){ if($nome_licenciadores[$w]==$licenciadores[$x]){ ?> checked="true"<?php $name_licenciadores=1;}} ?> editar="change" value="<?php echo $id_licenciadores[$w]; ?>" id="licenciadores<?php echo $id_licenciadores[$w]; ?>" onclick="bs(this)"><label for="licenciadores<?php echo $id_licenciadores[$w]; ?>"><?php echo $nome_licenciadores[$w]; ?></label>
											</td>
											<?php $w++;} ?>
										</tr>
										<?php $colunas_licenciadores=$colunas_licenciadores+2;} ?>
										</table></div></li>
										<li><b>Estudio: </b><a href="javascript:toggleDiv('myContent4');">Ver/Ocultar</a>
										<div class="generos"><table id="myContent4" style="display: none; margin: 0px;">
										<?php $w=0; for ($i=0; $i<$linhas_estudio; $i++) { ?>
										<tr>
											<?php while($w<$total_estudio && $w<$colunas_estudio){ $name_estudio=0; ?>
											<td>
												<input type="checkbox" name="estudio[]" <?php for($x=0; $x<count($estudio); $x++){ if($nome_estudio[$w]==$estudio[$x]){ ?> checked="true"<?php $name_estudio=1;}} ?> editar="change" value="<?php echo $id_estudio[$w]; ?>" id="estudio<?php echo $id_estudio[$w]; ?>" onclick="bs(this)"><label for="estudio<?php echo $id_estudio[$w]; ?>"><?php echo $nome_estudio[$w]; ?></label>
											</td>
											<?php $w++;} ?>
										</tr>
										<?php $colunas_estudio=$colunas_estudio+2;} ?>
										</table></div></li>
										<li><b>Fonte: </b>
										<select name="fonte">
											<?php for($i=0; $i<count($id_fonte1); $i++){ ?>
											<option value="<?php echo $id_fonte1[$i]; ?>" <?php if($id_fonte1[$i]==$id_fonte){ ?>selected="selected"<?php } ?>><?php echo $nome_fonte[$i]; ?></option>
											<?php } ?>
										</select></li>
										<li><b>Géneros: </b><a href="javascript:toggleDiv('myContent2');">Ver/Ocultar</a>
										<div class="generos"><table id="myContent2" style="display: none; margin: 0px;">
										<?php $w=0; for ($i=0; $i<$linhas_generos; $i++) { ?>
										<tr>
											<?php while($w<$total_generos && $w<$colunas_generos){ $name_genero=0; ?>
											<td>
												<input type="checkbox" name="generos[]" <?php for($x=0; $x<count($generos); $x++){ if($nome_generos[$w]==$generos[$x]){ ?> checked="true"<?php $name_genero=1;}} ?> editar="change" value="<?php echo $id_generos[$w]; ?>" id="generos<?php echo $id_generos[$w]; ?>" onclick="bs(this)"><label for="generos<?php echo $id_generos[$w]; ?>"><?php echo $nome_generos[$w]; ?></label>
											</td>
											<?php $w++;} ?>
										</tr>
										<?php $colunas_generos=$colunas_generos+2;} ?>
										</table></div></li>
										<li class="dados width-auto"><b>Duração: </b><x id="duracao" class="editable"><?php echo $duracao;?></x></li>
										<li><b>Classificação: </b>
										<select name="classificacao">
											<?php for($i=0; $i<count($id_classificacao1); $i++){ ?>
											<option value="<?php echo $id_classificacao1[$i]; ?>" <?php if($id_classificacao1[$i]==$id_classificacao){ ?>selected="selected"<?php } ?>><?php echo $nome_classificacao[$i]; ?></option>
											<?php } ?>
										</select></li>
										<?php }else{ ?>
										<li><b>Tipo: </b><?php echo $tipo;?></li>
										<li><b>Episódios: </b><?php echo $episodios;?></li>
										<li><b>Estado: </b><?php echo $estado_anime;?></li>
										<li><b>Lançamento: </b><?php echo $lancamento;?></li>
										<li><b>Temporada: </b><?php echo $temporada;?></li>
										<li><b>Produtores: </b><?php if (empty($produtores)) {echo '';}
											else{ echo $produtores[] = implode(', ', $produtores);}?></li>
										<li><b>Licenciadores: </b><?php if (empty($licenciadores)) {echo '';}
											else{ echo $licenciadores[] = implode(', ', $licenciadores);}?></li>
										<li><b>Estudio: </b><?php if (empty($estudio)) {echo '';}
											else{echo $estudio[] = implode(', ', $estudio);}?></li>
										<li><b>Fonte: </b><?php echo $fonte;?></li>
										<li><b>Géneros: </b><?php if (empty($generos)) {echo '';}
											else{ echo $generos[] = implode(', ', $generos);}?></li>
										<li><b>Duração: </b><?php echo $duracao;?></li>
										<li><b>Classificação: </b><?php echo $classificacao;?></li>
										<?php } ?>
									</ul>
								</section>
							</div>
							
							<!-- Content -->
							<div id="content" class="8u skel-cell-important">
								<section>
									<header class="major" name="anime">
									<table>
										<tr>
											<td name="animenome" width="100%">
												<div class="titulo"><div class="dados h2-dados"><h2 id="titulo" <?php if($editar=="true"){ ?>class="editable"<?php } ?>><?php echo $nome;?></h2></div></div>
											</td>
											<td name="nota">
												<a>Nota:</a><h1><?php echo $nota_media ?></h1><a><?php echo $total_notas_utilizadores ?> pess.</a>
											</td>
										</tr>
									</table>
									</header>
									<h3>Sinopse:</h3>
									<div class="dados h2-dados"><p id="sinopse" <?php if($editar=="true"){ ?>class="editable1"<?php } ?>><?php echo $sinopse; ?></p></div>
									<?php if(empty($personagens_nome)){}elseif(isset($tipo_sessao)){ if($tipo_sessao==1){ ?>
									<h3><?php if($editar!="personagens" && $editar!="staff"){if(isset($tipo_sessao)){if($tipo_sessao==1 && $editar!="true"){ ?><a name="mouse.point" onclick="location.href='anime_informacao?idanime=<?php echo $animeid ?>&edit=personagens'" title="Editar Personagens">🔧</a> Personagens:<?php }}}else{ ?> Personagens:</h3>
									<?php }}else{ ?><h3>Personagens:<?php } ?></h3>
									<?php if($editar!="personagens"){ ?>
									<table>
										<?php $y=0;
										while(count($personagens_nome)>$y){ ?>
										<tr id="<?php echo $id_personagens[$y]; ?>">
    										<td width="27" height="40">
    											<a><img src="data:image/jpeg;base64, <?php echo $personagens_imagem[$y]?>" width="23" vspace="4" border="0" hspace="8" height="32"></a>
											</td>
    										<td>
												<a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[$y];?>"><?php echo $personagens_nome[$y];?></a>
											</td>
											<td style="text-align:right">
												<?php echo $personagens_tipo[$y];?>
											</td>
  										</tr>
										<?php $y++;} ?>
									</table>
									<?php }else{ ?>
									<table>
 										<?php $y=0;
										while(count($personagens_nome)>$y){ ?>
  										<tr id="<?php echo $id_personagens[$y]; ?>">
    										<td width="27" height="40">
    											<a><img src="data:image/jpeg;base64, <?php echo $personagens_imagem[$y]?>" width="23" vspace="4" border="0" hspace="8" height="32"></a>
											</td>
    										<td>
												<a href="personagem_informacao?idpersonagem=<?php echo $id_personagens[$y];?>"><?php echo $personagens_nome[$y];?></a>
												<x onclick="location.href='remover_personagens_script?idanime=<?php echo $id_animes ?>&idpersonagens=<?php echo $id_personagens[$y]; ?>'">🗑️</x>
											</td>
											<td style="text-align:right">
												<select name="tipopersonagem<?php echo $id_personagens[$y] ?>">
												<option value="Principal" <?php if($personagens_tipo[$y]=="Principal"){ ?>selected="selected"<?php } ?>>Principal</option>
												<option value="Secundário" <?php if($personagens_tipo[$y]=="Secundário"){ ?>selected="selected"<?php } ?>>Secundário</option>
												</select>
											</td>
  										</tr>
  										<?php $y++;} ?>
									</table>
									<?php }}if(isset($personagens_nome)){}elseif(isset($tipo_sessao)){if($tipo_sessao==1){
									$total_cargo=count($nome_cargo);
									$colunas_cargo=4;
									$linhas_cargo=$total_cargo/$colunas_cargo;
									?>
									<h3><?php if($editar!="personagens" && $editar!="staff"){if(isset($tipo_sessao)){ if($tipo_sessao==1 && $editar!="true"){ ?><a name="mouse.point" onclick="location.href='anime_informacao?idanime=<?php echo $animeid ?>&edit=personagens'" title="Editar Personagens">🔧</a> Personagens:</h3><?php }}}}} ?>
									<?php if(empty($staff_nome)){}elseif(isset($tipo_sessao)){ if($tipo_sessao==1){ ?>
									<h3><?php if($editar!="personagens" && $editar!="staff"){if(isset($tipo_sessao)){if($tipo_sessao==1 && $editar!="true"){ ?><a name="mouse.point" onclick="location.href='anime_informacao?idanime=<?php echo $animeid ?>&edit=Equipa'" title="Editar Equipa">🔧</a> Equipa:<?php }}}else{ ?><h3>Equipa:</h3>
									<?php }}else{ ?><h3>Equipa:<?php } ?></h3>
									<?php if($editar!="staff"){ ?>
									<table>
 										<?php $y=1;$w=0;
										while(count($staff_nome)>=$y){ ?>
  										<tr>
    										<td width="27" height="40">
    											<a><img src="data:image/jpeg;base64, <?php echo $staff_imagem[$w]?>" width="23" vspace="4" border="2" hspace="8" height="32"></a>
											</td>
    										<td>
												<a href="pessoa_informacao?idpessoa=<?php echo $id_staff[$w];?>"><?php echo $staff_nome[$w];?></a>
											</td>
											<td style="text-align:right">
												<?php if (empty($id_posicao_staff)) {echo '';}
												else{ echo $id_posicao_staff[$id_staff[$w]][] = implode(', ', $id_posicao_staff[$id_staff[$w]]);} ?>
											</td>
  										</tr>
  										<?php $y++;$w++; } ?>
									</table>
									<?php }else{ ?>
									<table>
 										<?php $y=0;
										while(count($staff_nome)>$y){ ?>
  										<tr>
    										<td width="27" height="40">
    											<a><img src="data:image/jpeg;base64, <?php echo $staff_imagem[$y]?>" width="23" vspace="4" border="2" hspace="8" height="32"></a>
											</td>
    										<td>
												<a href="pessoa_informacao?idpessoa=<?php echo $id_staff[$y];?>"><?php echo $staff_nome[$y];?></a>
												<x onclick="location.href='remover_staff_script?idanime=<?php echo $id_animes ?>&idstaff=<?php echo $id_staff[$y]; ?>'">🗑️</x>
											</td>
											<td style="text-align:right">
												<a href="javascript:toggleDiv('myContent4');">Ver/Ocultar</a>
											</td>
  										</tr>
  										<?php
										$total_cargo=count($nome_cargo);
										$colunas_cargo=4;
										$linhas_cargo=$total_cargo/$colunas_cargo;
										?>
										<tr>
											<td colspan="3" id="myContent4" style="display: none; margin: 0px;">
												<table style="margin: 0;">
													<?php $w=0; for($i=0; $i<$linhas_cargo; $i++) { ?>
													<tr><?php while($w<$total_cargo && $w<$colunas_cargo){ ?>
														<td>
															<input type="checkbox" name="staff<?php echo $id_staff[$y] ?>[]" <?php if(isset($id_posicao_staff[$id_staff[$y]])){for($x=0; $x<count($id_posicao_staff[$id_staff[$y]]); $x++){ if($nome_cargo[$w]==$id_posicao_staff[$id_staff[$y]][$x]){ ?> checked="true"<?php }}} ?> editar="change" value="<?php echo $id_cargo[$w]; ?>" id="licenciadores<?php echo $id_cargo[$w]; ?>" onclick="bs(this)">
															<label for="licenciadores<?php echo $id_cargo[$w]; ?>"><?php echo $nome_cargo[$w]; ?></label>
														</td><?php $w++;} ?>
													</tr><?php $colunas_cargo=$colunas_cargo+4;} ?>
												</table>
											</td>
										</tr>
										<?php $colunas_cargo=$colunas_cargo+3;$y++;} ?>
									</table>
									<?php }} if(isset($staff_nome)){}elseif(isset($tipo_sessao)){if($tipo_sessao==1){
									$total_cargo=count($nome_cargo);
									$colunas_cargo=4;
									$linhas_cargo=$total_cargo/$colunas_cargo;
									?>
									<h3><?php if($editar!="personagens" && $editar!="staff"){if(isset($tipo_sessao)){if($tipo_sessao==1 && $editar!="true"){ ?><a name="mouse.point" onclick="location.href='anime_informacao?idanime=<?php echo $animeid ?>&edit=staff'" title="Editar Equipa">🔧</a> Equipa:</h3><?php }}}}} ?>
								</section>
							</div>
						</div>
					</div>
				</form>
				<!-- /Page -->
				
           					<?php if(isset($_SESSION["nome"])){ ?>
            					<div id="element_to_pop_up">
									<div class="dados">
									<?php if($editar!="staff" && $editar!="personagens"){ ?>
									<form name="EditAnime" method="post" action="adicionar_animes_script?idanime=<?php echo $animeid; ?>&totalep=<?php echo $episodios; ?>"><h3>+ Editar <?php echo $nome;?>:</h3><table>
										<tr>
    										<td>
    											Estado:
											</td>
    										<td>
												<select name="estado" onChange="check(this.value, <?php echo $episodios; ?>)">
												<option value="2" <?php if($add_idestado==2){ ?>selected="selected"<?php } ?>>Assistindo</option>
												<option value="1" <?php if($add_idestado==1){ ?>selected="selected"<?php } ?> >Completo</option>
												<option value="3" <?php if($add_idestado==3){ ?>selected="selected"<?php } ?>>Em espera</option>
												<option value="4" <?php if($add_idestado==4){ ?>selected="selected"<?php } ?>>Desistiu</option>
												<option value="5" <?php if($add_idestado==5){ ?>selected="selected"<?php } ?>>Planeia assistir</option>
												</select>
											</td>
  										</tr>
  										<tr>
    										<td>
    											Episódios vistos:
											</td>
    										<td>
												<input type="number" name="episodios" id="episodios_vistos" min="0" max="<?php echo $episodios;?>" placeholder="Ep." style="width:65px;display:contents;" value="<?php echo $add_episodiosvistos ?>">/<?php echo $episodios;?>
											</td>
  										</tr>
   										<tr>
    										<td>
    											Nota:
											</td>
    										<td>
												<select name="nota">
												<option value="NULL">Escolher nota</option>
												<option value="10" <?php if($add_nota==10){ ?>selected="selected"<?php } ?>>(10) Perfeito</option>
												<option value="9" <?php if($add_nota==9){ ?>selected="selected"<?php } ?>>(9) Excelente</option>
												<option value="8" <?php if($add_nota==8){ ?>selected="selected"<?php } ?>>(8) Ótimo</option>
												<option value="7" <?php if($add_nota==7){ ?>selected="selected"<?php } ?>>(7) Muito bom</option>
												<option value="6" <?php if($add_nota==6){ ?>selected="selected"<?php } ?>>(6) Bom</option>
												<option value="5" <?php if($add_nota==5){ ?>selected="selected"<?php } ?>>(5) Médio</option>
												<option value="4" <?php if($add_nota==4){ ?>selected="selected"<?php } ?>>(4) Mau</option>
												<option value="3" <?php if($add_nota==3){ ?>selected="selected"<?php } ?>>(3) Muito mau</option>
												<option value="2" <?php if($add_nota==2){ ?>selected="selected"<?php } ?>>(2) Horrível</option>
												<option value="1" <?php if($add_nota==1){ ?>selected="selected"<?php } ?>>(1) Péssimo</option>
												</select>
											</td>
  										</tr>
  										<tr>
  											<td colspan="2">
  												<input type="submit" name="adicionar" style="font-size: 15px;" value="Adicionar">
  												<input type="submit" name="apagar" style="font-size: 15px;" value="Remover">
  											</td>
  										</tr>
									</table></form>
									<?php }elseif($editar=="staff"){ ?>
										<form name="EditAnime" method="post" action="adicionar_staff_script?idanime=<?php echo $animeid; ?>"><h3>Adicionar novo membro ao anime:</h3>
										<table><tr>
    										<td nowrap>
    											Membro da equipa:
											</td>
    										<td colspan="2">
    											<?php 
												//Retirar da tabela pessoa (todos)
												$sqlget = "SELECT * FROM `pessoa` ORDER BY `pessoa`.`nome` ASC ";
    											$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
												while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
													$id_pessoa[] = $row['id_pessoa'];
													$nome_pessoa[] = $row['nome'];
    											}
												?>
												<select name="pessoa">
													<?php for($i=0; $i<count($id_pessoa); $i++){ ?>
													<option value="<?php echo $id_pessoa[$i]; ?>"><?php echo $nome_pessoa[$i]; ?></option>
													<?php } ?>
												</select>
											</td>
  										</tr>
										<tr>
											<td colspan="3">
												Cargos no anime:
											</td>
										</tr>
 										<tr>
 										<td colspan="3"><table>
  										<?php
										$total_cargo=count($nome_cargo);
										$colunas_cargo=3;
										$linhas_cargo=$total_cargo/$colunas_cargo;
										$w=0; for($i=0; $i<$linhas_cargo; $i++) { ?>
										<tr><?php while($w<$total_cargo && $w<$colunas_cargo){ ?>
											<td nowrap>
												<input type="checkbox" name="cargos[]" editar="change" value="<?php echo $id_cargo[$w]; ?>" id="cargo1<?php echo $id_cargo[$w]; ?>" onclick="bs(this)">
												<label for="cargo1<?php echo $id_cargo[$w]; ?>"><?php echo $nome_cargo[$w]; ?></label>
											</td><?php $w++;} ?>
										</tr>
 										<?php $colunas_cargo=$colunas_cargo+3;} ?>
 										</table></td>
 										</tr>
  										<tr>
  											<td colspan="3">
  												<center><input type="submit" name="staff" style="font-size: 15px;" value="Adicionar"></center>
  											</td>
  										</tr></table>
										</form>
									<?php }elseif($editar=="personagens"){ ?>
										<form name="EditAnime" method="post" action="adicionar_personagem_script?idanime=<?php echo $animeid; ?>"><h3>Adicionar personagem ao anime:</h3>
   										<?php 
										//Retirar da tabela personagens (todos)
										$sqlget = "SELECT * FROM `personagens` ORDER BY `personagens`.`nome` ASC ";
    									$sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
										while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
											$x=0;
											if(isset($id_personagens)){
											for($i=0; $i<count($id_personagens); $i++){
												if($id_personagens[$i]==$row['id_personagens']){
													$x=1;
												}
											}}
											if($x==0){
												$id_personagem[] = $row['id_personagens'];
												$nome_personagens[] = $row['nome'];
											}
    									}
										if(isset($id_personagem)){
										?>
										<table>
   										<tr>
    										<td nowrap>
    											Personagem:
											</td>
    										<td>
												<select name="personagemid">
													<?php for($i=0; $i<count($id_personagem); $i++){ ?>
													<option value="<?php echo $id_personagem[$i]; ?>"><?php echo $nome_personagens[$i]; ?></option>
													<?php } ?>
												</select></li>
											</td>
  										</tr>
										<tr>
											<td>
												Tipo personagem:
											</td>
											<td>
												<select name="tipopersonagem">
												<option value="Principal">Principal</option>
												<option value="Secundário">Secundário</option>
												</select>
											</td>
										</tr>
  										<tr>
  											<td colspan="2">
  												<center><input type="submit" name="personagem" style="font-size: 15px;" value="Adicionar"></center>
  											</td>
  										</tr></table>
  										<?php }else{ ?>
											Não existem novos personagens para adicionar.
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