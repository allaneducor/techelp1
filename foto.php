<?php
session_start();
require_once "conexao.php";
$tipos = array('png', 'jpg', 'jpeg');
if (isset($_POST["enviar"])){
    $nomearquivo = $_FILES['arquivo']['name'];
    $extensao = pathinfo($nomearquivo, PATHINFO_EXTENSION);
    if(in_array($extensao, $tipos)) {
        $dadoimg = addslashes(file_get_contents($_FILES['arquivo']['tmp_name']));
        $propimg = getimageSize($_FILES['arquivo']['tmp_name']);
        try{
            $sql = "INSERT INTO users (nome, password, email, tipo) VALUES ('{$_SESSION["nome"]}', '{$_SESSION["password"]}', '{$_SESSION["email"]}', '2')";
            mysqli_query($link, $sql);

            $sql2 = "INSERT INTO infotecnico (email, cpf, celular) VALUES ('{$_SESSION["email"]}', '{$_SESSION["cpf"]}', '{$_SESSION["celular"]}')";
            mysqli_query($link, $sql2);
            
            $sql3 = "INSERT INTO fotoperfil(email, tipoimagem, dadosimagem) VALUES('{$_SESSION["email"]}', '{$propimg['mime']}', '{$dadoimg}')";
            if(mysqli_query($link, $sql3)) {
                header("location: logout.php");
            }
        } catch (Exception $err) {
            echo 'Exceção capturada: ',  $err->getMessage(), "\n";
        }
    } else{
        echo "Tipo de arquivo inválido!";
    }
}
?>
<HTML>
<HEAD>
<TITLE>Diga Xis!</TITLE>
<link rel="icon" href="img/logoicon.png">
<link href="imageStyles.css" rel="stylesheet" type="text/css" />
</HEAD>
<BODY>
    <form method="POST" enctype="multipart/form-data" action="">
        <input type="file" name="arquivo"> 
        <input type="submit" name="enviar">
        <a href="logout.php"><button type="button">Cancelar Cadastro</button></a>
    </form>
    </div>
</BODY>
</HTML>