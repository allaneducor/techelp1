<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/logoicon.png">
    <title>Cadastro</title>
    <link rel="stylesheet" href="css/cadastro.css">
    <?php require "conexao.php";?>
    <?php
    // resetando variáveis
    $nome = $password = $confirm_senha = $email = "";
    $erro_nome = $erro_senha = $confirm_password_err = $email_err="";
    session_start();
    if(isset($_SESSION["logado"]) && $_SESSION["logado"] == true) {
        if ($_SESSION["tipo"] == 1) {
            header("location: analise.html");
        } elseif($_SESSION["tipo"] == 2) {
            header("location: perfil.php");
        }
        exit;
    }
    if(isset($_SESSION["cadastrando"])) {
        if ($_SESSION["cadastrando"] == 1) {
            header("location: cadastroDois.php");
        } elseif ($_SESSION["cadastrando"] == 2) {
            header("location: foto.php");
        }
    exit;
    }
    if(isset($_SESSION["erro"])) {
        echo $_SESSION["erro"]."</br>";
        unset($_SESSION["erro"]);
    }
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // verificando nome do usuário
        if(empty(trim($_POST["nome"]))){
            $erro_nome = "Por favor, preencha o campo";     
        } elseif(!preg_match('/^[a-zA-Z\\s]+$/', trim($_POST["nome"]))){
            $erro_nome = "O campo apenas pode conter letras e espaços";
        } else{
            $nome = trim($_POST["nome"]);
        }
        //validando email
        if (empty(trim($_POST["email"]))) {
            $email_err = "Por favor, preencha o campo";
        } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $email_err = "E-mail inválido";
        }else{
            $sql = "SELECT id FROM users WHERE email = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                // conectando o parâmetro e a variável
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                
                // usando parâmetros
                $param_email = trim($_POST["email"]);
                
                // tentando executar o pedido sql
                if(mysqli_stmt_execute($stmt)){
                    // guardando resultado
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $email_err = "Este e-mail já está cadastrado";
                    } else {$email = trim($_POST["email"]);}
                }
            }
        }
        // Validando senha
        if(empty(trim($_POST["password"]))){
            $erro_senha = "Por favor, preencha o campo";     
        } elseif(strlen(trim($_POST["password"])) < 8){
            $erro_senha = "A senha deve conter no mínimo 8 caracteres";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Validando confirmação de senha
        if(empty(trim($_POST["confirm_senha"]))){
            $confirm_password_err = "Confirme a senha";
        } else{
            $confirm_senha = trim($_POST["confirm_senha"]);
            if(empty($erro_senha) && ($password != $confirm_senha)){
                $confirm_password_err = "Senha e confirmação são diferentes";
            }
        }
        //se os erros estiverem vazios, enviar para o bdd
        if(empty($erro_nome) && empty($erro_senha) && empty($confirm_password_err) && empty($email_err)){
            $sql = "INSERT INTO users (nome, password, email, tipo) VALUES (?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "sssi", $param_nome, $param_senha, $param_email, $tipo);
                $param_nome = $nome;
                $param_senha = password_hash($password, PASSWORD_DEFAULT); // Encripta a senha
                $param_email = $email;
                $tipo = $_POST["tipo"];
                // Tentando executar
                if($tipo == 2) {
                    // Redireciona para a tela de cadastro técnico
                    $_SESSION["email"] = $email; 
                    $_SESSION["nome"] = $nome;
                    $_SESSION["password"] = password_hash($password, PASSWORD_DEFAULT);
                    $_SESSION["cadastrando"] = 1;
                    header("location: cadastroDois.php");
                }

                if($tipo == 1){
                    if(mysqli_stmt_execute($stmt)){
                        // Redireciona para a tela de login
                        header("location: index.php");
                    }
                }
                // fechando pedido php
                mysqli_stmt_close($stmt);
            }else{
                echo "Algo deu errado! Tente novamente mais tarde";
            }
        }
            // Close connection
            mysqli_close($link);
    }
?>
</head>

<body>
    <div class="cadastro">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" placeholder="Nome" name="nome">
            <span class="mensagem_erro"><?php if (!empty($erro_nome)){echo "Erro: ". $erro_nome;}?></span>
            <br>

            <input type="text" placeholder="Email" name = "email">
            <span class="mensagem_erro"><?php if (!empty($email_err)){echo "Erro: ". $email_err;}?></span>  
            <br>

            <input type="password" placeholder="Senha" name = "password">
            <span class="mensagem_erro"><?php if (!empty($erro_senha)){echo "Erro: ". $erro_senha;}?></span>  
            <br>

            <input type="password" placeholder="Confirmar Senha" name="confirm_senha">
            <span class="mensagem_erro"><?php if (!empty($confirm_password_err)){echo "Erro: ". $confirm_password_err;}?></span>
            </br>
            <input type="radio" id = "clientebutton" name="tipo" value="1"><label for="clientebutton">Cliente</label><input type="radio" id = "tecnicobutton"name="tipo" value="2" ><label for="tecnicobutton">Técnico</label>

            <br><br>
            <button>Cadastrar-se</button>
        </form>
        <img src="img/enfeite1.png" alt="alysson">  
    </div>
    
</body>
</html>