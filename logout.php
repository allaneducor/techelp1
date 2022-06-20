<?php
// Inicia a sessão
session_start();
 
// Remove todo o conteúdo da sessão
$_SESSION = array();
 
// elimina a sessão
session_destroy();
 
// volta p tela de login
header("location: index.php");
exit;
?>