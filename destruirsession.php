<?php

if(isset($_POST['continuar'])){
    unset($_SESSION['token']);
    unset($_SESSION['payment']);
    header('Location: loja.php');
    die();
}
elseif(isset($_POST['ver'])){
    unset($_SESSION['token']);
    unset($_SESSION['payment']);
    header('Location: #.php');
    die();
}

?>