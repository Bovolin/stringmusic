<?php
session_start();
if(!$_SESSION['usuario']){
    header ('Location: https://localhost/stringmusic/login.php');
    exit;
}

?>