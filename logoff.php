<?php
    setcookie("codUsu", null, -1);
    setcookie("idUsuario", null, -1);
    setcookie("permissao", null, -1);
    setcookie("nome", null, -1);
    setcookie("senha", null, -1);

    header('location: ./login.php');
?>