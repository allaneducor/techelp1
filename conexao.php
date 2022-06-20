<?php
    /* credenciais db*/
    define('db_server', 'localhost');
    define('db_username', 'root');
    define('db_password', '');
    define('db_name', 'techelp');
    
    /* conexao db */
    $link = mysqli_connect(db_server, db_username, db_password, db_name);
    
    // checando conexão
    if($link == false){
        die("Erro de conexão: " . mysqli_connect_error());
    }
?>