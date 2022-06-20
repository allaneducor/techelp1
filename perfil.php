<!DOCTYPE html>
<?php
    require "conexao.php";
    session_start();
    if(isset($_SESSION["cadastrando"])) {
        if ($_SESSION["cadastrando"] == 1) {
            header("location: cadastroDois.php");
        } elseif ($_SESSION["cadastrando"] == 2) {
            header("location: foto.php");
        }
        exit;
    }

    unset($_SESSION["password"]);
    $result = $link->query("SELECT dadosimagem FROM fotoperfil WHERE email = '{$_SESSION["login"]}'");
    $sql = "SELECT nome, email, tipo FROM users WHERE email = '{$_SESSION["login"]}'";
    if($stmt = mysqli_prepare($link, $sql)) {
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $nome, $email, $tipo);
                if (mysqli_stmt_fetch($stmt)) {
                    $_SESSION["tipo"] = $tipo;
                    $_SESISON["nome"] = $nome;
                    $_SESSION["email"] = $email;
                }
            }
        }
    }
?>
<html>
<head>
    <title>Perfil</title>
    <link rel="icon" href="img/logoicon.png">
    <link rel="stylesheet" href="css/perfil.css">
    <?php require_once "conexao.php" ?>
</head>
<body>

<?php if($result->num_rows > 0){ ?>
    <div class="center">
        <?php
            if($tipo == 2) {
                echo "tecnico";
            }elseif ($tipo == 1) {
                echo "cliente";
            }
        ?>
        <div style="width: min-content;margin-left: auto; margin-right: auto;" >
            <?php while($row = $result->fetch_assoc()){ ?> 
                <img class = "foto" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['dadosimagem']); ?>" /> 
            <?php } ?>
        </div> 
            <?php } ?>
            <?php
                echo $nome."</br>";
                echo $email."</br>";
            ?>
            <a href="logout.php"><button type="button">Sair</button></a>
    </div>
</body>
</html>
