<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/logoicon.png">
    <title>Cadastro</title>
    <link rel="stylesheet" href="css/cadastroDois.css">
    <?php require "conexao.php";?>
    <?php
    function validaCPF($cpf) { 
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf);
        // Números repetidos?
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
    
        // calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($soma = 0, $i = 0; $i < $t; $i++) {
                $soma += $cpf[$i] * (($t + 1) - $i);
            }
            $soma = ((10 * $soma) % 11) % 10;
            if ($cpf[$i] != $soma) {
                return false;
            }
        }
        return true;
    };
    session_start();

    if(empty($_SESSION["email"])
    || empty($_SESSION["nome"])
    || empty($_SESSION["password"])) {
        $_SESSION = array();
        $_SESSION["erro"] = "Algo deu errado! Por favor, faça o cadastro novamente!";
        header("cadastro.php");
    }
    if(isset($_SESSION["logado"]) && $_SESSION["logado"] == true) {
        if ($_SESSION["tipo"] == 1) {
            header("location: analise.html");
        } elseif($_SESSION["tipo"] == 2) {
            header("location: perfil.php");
        }
        exit;
    }
    if(empty($_SESSION["cadastrando"])) {
        header(location: "cadastro.php");
    } elseif(isset($_SESSION["cadastrando"])) {
        if ($_SESSION["cadastrando"] == 2) {
            header("location: foto.php");
            exit;
        }
    }
    $cpf = $celular = "";
    $erro_cpf = $erro_celular = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
            // validando cpf
        if(empty(trim($_POST["cpf"]))) {
            $erro_cpf = "Por favor, preencha o campo";
        } elseif(validaCPF(trim($_POST["cpf"])) == false) {
            $erro_cpf = "CPF Inválido";
        } else{
            $_SESSION["cpf"] = trim($_POST["cpf"]);
        }

        // validando celular
        if(empty(trim($_POST["celular"]))) {
            $erro_celular = "Por favor, preencha o campo";
        } else {
            $_SESSION["celular"] = trim($_POST["celular"]);
        }
        if(empty($erro_celular) && empty($erro_cpf)){
            $_SESSION["cadastrando"] = 2;
            header("location: foto.php");
        }
    }
    ?>
</head>
<body>

<form method="post" action="">
    
    <div id="area">

        <div id="titulo">
            
            <h2>Cadastro tecnico</h2>

            <div id="dados">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="text" placeholder="Celular (XX XXXXXXXX ou XXXXXXXXXX)" name = "celular" pattern="\d{2}\s?\d{8}" autofocus required title="XX XXXXXXXX ou XXXXXXXXXX"/>
                    <span class="mensagem_erro"><?php if (!empty($erro_celular)){echo "Erro: ". $erro_celular;}?></span>
                    <br>
                    <input type="text" placeholder="CPF (XXX.XXX.XXX-XX ou XXXXXXXXXXX)" name = "cpf" pattern="\d{3}\.?\d{3}\.?\d{3}-?\d{2}" autofocus required title="XXX.XXX.XXX-XX ou XXXXXXXXXXX"/>
                    <span class="mensagem_erro"><?php if (!empty($erro_cpf)){echo "Erro: ". $erro_cpf . "<br>";}?></span>
                    <button type="submit">Proximo</button>
                    <a href="logout.php"><button type="button">Cancelar Cadastro</button></a>
                </form>
            </div>
        </div>
    </div>
</form>
</body>
</html>