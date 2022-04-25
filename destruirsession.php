<?php

if(isset($_POST['c'])){
    unset($_SESSION['token']);
    unset($_SESSION['payment']);
    header('Location: loja.php');
    die();
}
elseif(isset($_POST['v'])){
    unset($_SESSION['token']);
    unset($_SESSION['payment']);
    header('Location: #.php'); //quando estiver pronto a página
    die();
}

?>