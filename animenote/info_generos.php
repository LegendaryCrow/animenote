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
	//Retirar informação da tabela generos
	$sqlget = "SELECT * FROM `generos` ORDER BY `generos`.`nome` ASC ";
    $sqldata = mysqli_query($con,$sqlget) or die (header('Location: procurar'));
	while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		$id_generos[] = $row['id_generos'];
		$nome_generos[] = $row['nome'];
		$descricao_generos[] = $row['descricao'];
    }
	$total_generos=count($nome_generos);
    ?>
		<title>Informação dos Géneros - <?php echo $nome_website; ?></title>
		<link rel="shortcut icon" href="data:image/jpeg;base64, <?php echo $favicon?>" type="image/x-icon" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
	</head>
	<body>
		<h2>Informação dos Géneros</h2>
 		<?php for ($i=0; $i<$total_generos; $i++) { ?>
  		<li><strong><?php echo $nome_generos[$i];?></strong> - <?php echo $descricao_generos[$i];?></li><br>
  		<?php } ?>
	</body>
</html>