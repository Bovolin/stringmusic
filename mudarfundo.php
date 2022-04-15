<?php
session_start();
include ("conexao.php");

if(isset($_SESSION['usuario'])){
    $session = $_SESSION['usuario'];
  
    foreach($mysqli->query("SELECT cd_usuario AS codigo FROM tb_usuario WHERE cd_usuario = '$session'") as $usuarios){
      $codigousuario = $usuarios['codigo'];
    }
}

if(isset($_FILES['fundo'])){
    $foto = $_FILES['fundo'];
    
    if($foto['error']){
        $_SESSION['pic_error'] = true;
        header("Location: painel.php");
        die();
    }
    if($foto['size'] > 2097152){
        $_SESSION['pic_error_size'] = true;
        header("Location: painel.php");
        die();
    }
    $pasta = "img/";
    $nomeoriginal = $foto['name'];
    $novonome = uniqid();
    $extensao = strtolower(pathinfo($nomeoriginal, PATHINFO_EXTENSION));

    if($extensao != 'jpg' && $extensao != 'png' && $extensao != 'jpeg') die("Tipo de arquivo não aceito! Somente é aceito .jpg, .png ou .jpeg");;

    $path = $pasta . $novonome . "." . $extensao;
    $mover = move_uploaded_file($foto["tmp_name"], $path);

    if($mover){
        $contafoto = "SELECT COUNT(cd_imagem) AS c FROM tb_imagem";
        $contafoto = $mysqli->query($contafoto);
        $contafoto = $contafoto->fetch_assoc();
        $resultado = $contafoto['c'] + 1;

        $inserefoto = "INSERT INTO tb_imagem (cd_imagem, nm_imagem, path, dt_imagem) VALUES ('$resultado', '$nomeoriginal', '$path', NOW())";
        $sql = $mysqli->query($inserefoto);

        $contafundo = $mysqli->query("SELECT COUNT(cd_fundo) AS f FROM tb_fundo");
        $contafundo = $contafundo->fetch_assoc();
        $contafundo_resultado = $contafundo['f'] + 1;

        $inserefundo = "INSERT INTO tb_fundo (cd_fundo, nm_fundo, cd_imagem) VALUES ('$contafundo_resultado', '$nomeoriginal', '$resultado')";
        $sql_fundo = $mysqli->query($inserefundo);

        $alterausuario = "UPDATE tb_usuario SET cd_fundo = '$contafundo_resultado' WHERE cd_usuario = '$codigousuario'";
        $update = $mysqli->query($alterausuario);

        $_SESSION['fotoinserida'] = true;
        header("Location: painel.php");
        die();
    }
    else{
        $_SESSION['fotorecusada'] = true;
        header("Location: painel.php");
        die(); 
    }
}
else{
    $_SESSION['fotorecusada'] = true;
    header("Location painel.php");
    die();
}

?>