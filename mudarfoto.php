<?php
session_start();
include ("conexao.php");

if(isset($_SESSION['usuario'])){
    $session = $_SESSION['usuario'];
  
    foreach($mysqli->query("SELECT cd_usuario AS codigo FROM tb_usuario WHERE cd_usuario = '$session'") as $usuarios){
      $codigousuario = $usuarios['codigo'];
    }
}

if(isset($_FILES['file'])){
    $foto = $_FILES['file'];
    
    if($foto['error']) die("Falha ao enviar foto!");
    if($foto['size'] > 2097152) die("O arquivo excedeu 2MB. Tamanho máximo: 2MB.");
    $pasta = "img/";
    $nomeoriginal = $foto['name'];
    $novonome = uniqid();
    $extensao = strtolower(pathinfo($nomeoriginal, PATHINFO_EXTENSION));

    if($extensao != 'jpg' && $extensao != 'png' && $extensao != 'jpeg') die("Tipo de arquivo não aceito! Somente é aceito .jpg .png ou .jpeg");;

    $path = $pasta . $novonome . "." . $extensao;
    $mover = move_uploaded_file($foto["tmp_name"], $path);

    if($mover){
        $contafoto = "SELECT COUNT(cd_imagem) AS c FROM tb_imagem";
        $contafoto = $mysqli->query($contafoto);
        $contafoto = $contafoto->fetch_assoc();
        $resultado = $contafoto['c'] + 1;

        $inserefoto = "INSERT INTO tb_imagem (cd_imagem, nm_imagem, path, dt_imagem) VALUES ('$resultado', '$nomeoriginal', '$path', NOW())";
        $sql = $mysqli->query($inserefoto);

        $alterausuario = "UPDATE tb_usuario SET cd_imagem = '$resultado' WHERE cd_usuario = '$codigousuario'";
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