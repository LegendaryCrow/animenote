<?php
session_start();
$con=new mysqli ("localhost","root","","animenote");
mysqli_set_charset($con, 'utf8mb4');

if($_POST["password"]!=$_POST["password_confirm"]){
	header('Location: registar?erro=0');
	exit();
}
$sqlget = "SELECT * from utilizador where nome='" . $_POST["utilizador"] . "'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: registar'));
$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
$confirmar_utilizador = $row['nome'];
if($confirmar_utilizador==$_POST["utilizador"]){
	header('Location: registar?erro=1');
	exit();
}
$sqlget = "SELECT * from utilizador where email='" . $_POST["email"] . "'";
$sqldata = mysqli_query($con,$sqlget) or die (header('Location: registar'));
$row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC);
$confirmar_email = $row['email'];
if($confirmar_email==$_POST["email"]){
	header('Location: registar?erro=2');
	exit();
}

$link=md5($_POST["email"]);

require 'PHPMailerAutoload.php';
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = 'smtp';
$mail->SMTPAuth = true;
$mail->Host = 'smtp.gmail.com';
$mail->Port = 465;
$mail->SMTPSecure = 'ssl';
 
$mail->Username = "animenote.web@gmail.com";
$mail->Password = "animenote123";
 
$mail->IsHTML(true);
$mail->SingleTo = true;

$mail->From = "animenote.web@gmail.com";
$mail->FromName = "Anime Note";

$mail->addAddress($_POST["email"]);
 
$mail->Subject = "Confirmar registo - Anime Note";
$mail->Body = <<<EOD
<head><meta charset="UTF-8"></head>
<body>
  <div style="background-color:#fff;margin:0 auto 0 auto;padding:30px 0 30px 0;color:#4f565d;font-size:13px;line-height:20px;font-family:'Helvetica Neue',Arial,sans-serif;text-align:left;">
    <center>
      <table style="width:550px;text-align:center">
        <tbody>
          <tr>
            <td style="padding:0 0 20px 0;border-bottom:1px solid #e9edee;">
              <a href="http://localhost/animenote/" style="display:block; margin:0 auto;" target="_blank">
                <img src="http://i.imgur.com/4l3bwZf.png" width="75" height="75" alt="Anime Note" style="border: 0px;">
              </a>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="padding:30px 0;"><p style="color:#1d2227;line-height:28px;font-size:22px;margin:12px 10px 20px 10px;font-weight:400;">Olá {$_POST["utilizador"]}, seja bem vindo ao Anime Note!</p>
              <p style="margin:0 10px 10px 10px;padding:0;">Já temos tudo pronto para o receber. Crie já a sua lista de animes.</p>
              <p style="margin:0 10px 10px 10px;padding:0;">Confirme o seu email clicando no botão para começar.</p>              <p>
                <a style="display:inline-block;text-decoration:none;padding:15px 20px;background-color:#2baaed;border:1px solid #2baaed;border-radius:3px;color:#FFF;font-weight:bold;" href="localhost/animenote/confirmar?url={$link}&name={$_POST["utilizador"]}" target="_blank">Confirmar email</a>
              </p>
              <p style="margin:0 10px 10px 10px;padding:0;">Se não efetuou este registo ignore o email.</p>
              <p style="margin:0 10px 10px 10px;padding:0;">Obrigado. A equipa Anime Note.</p>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="padding:30px 0 0 0;border-top:1px solid #e9edee;color:#9b9fa5">
              Se tiver alguma questão pode contactar-nos por <a style="color:#666d74;text-decoration:none;" href="mailto:animenote.web@gmail.com" target="_blank">animenote.web@gmail.com</a>
            </td>
          </tr>
        </tbody>
      </table>
    </center>
  </div>
</body>
EOD;
!$mail->Send();
echo $insere="INSERT INTO `utilizador`(`nome`, `password`, `genero`, `data_nascimento`, `email`, `localizacao`, `imagem`, `ativacao`) VALUES ('" . $_POST["utilizador"] . "','" . md5($_POST["password"]) . "','Desconhecido',NULL,'" . $_POST["email"] . "',NULL,NULL,1)";
mysqli_query($con, $insere) or die (header('Location:registar?sucesso=2'));
header('Location:registar?sucesso=1');
exit();
?>