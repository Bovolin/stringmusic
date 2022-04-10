<?php
include("conexao.php");
include("verifica_login.php");

//Pegar variáveis
$cod_user = $_SESSION['usuario']; //cd_usuario
$star = $_POST['input-star']; //qt_feedback
$comment = $_POST['comment']; //nm_feedback
$name_prod = $_POST['nm_prod']; //nm_prod -> cd_servico || cd_interpretacao || cd_insturmento

//Verificar tipo de produto
foreach($mysqli->query("SELECT cd_interpretacao AS cd_interp FROM tb_interpretacao WHERE nm_interpretacao = '$name_prod'") as $cod_interp){
    $cod_prod = $cod_interp['cd_interp'];

    //Contador de feedback
    $cont_fb = $mysqli->query("SELECT COUNT(*) AS feedback FROM tb_feedback");
    $cont_fb = $cont_fb->fetch_assoc();
    $result_fb = $cont_fb['feedback'] + 1;

    //Inserir feedback
    $insert_fb = $mysqli->query("INSERT INTO tb_feedback (cd_feedback, qt_feedback, dt_feedback, nm_feedback, cd_usuario, cd_interpretacao) VALUES ('$result_fb', '$star', NOW(), '$comment', '$cod_user', '$cod_prod')");

    $_SESSION['inserir_feedback'] = true;
    header('Location: produto.php?p=' . $name_prod);
}
foreach($mysqli->query("SELECT cd_servico AS cd_serv FROM tb_servico WHERE nm_servico = '$name_prod'") as $cod_serv){
    $cod_prod = $cod_serv['cd_serv'];

    //Contador de feedback
    $cont_fb = $mysqli->query("SELECT COUNT(*) AS feedback FROM tb_feedback");
    $cont_fb = $cont_fb->fetch_assoc();
    $result_fb = $cont_fb['feedback'] + 1;

    //Inserir feedback
    $insert_fb = $mysqli->query("INSERT INTO tb_feedback (cd_feedback, qt_feedback, dt_feedback, nm_feedback, cd_usuario, cd_servico) VALUES ('$result_fb', '$star', NOW(), '$comment', '$cod_user', '$cod_prod')");

    $_SESSION['inserir_feedback'] = true;
    header('Location: prodserv.php?s=' . $name_prod);
}
foreach($mysqli->query("SELECT cd_instrumento AS cd_inst FROM tb_instrumento WHERE nm_instrumento = '$name_prod'") as $cod_inst){
    $cod_prod = $cod_inst['cd_inst'];
    
    //Contador de feedback
    $cont_fb = $mysqli->query("SELECT COUNT(*) AS feedback FROM tb_feedback");
    $cont_fb = $cont_fb->fetch_assoc();
    $result_fb = $cont_fb['feedback'] + 1;

    //Inserir feedback
    $insert_fb = $mysqli->query("INSERT INTO tb_feedback (cd_feedback, qt_feedback, dt_feedback, nm_feedback, cd_usuario, cd_instrumento) VALUES ('$result_fb', '$star', NOW(), '$comment', '$cod_user', '$cod_prod')");

    $_SESSION['inserir_feedback'] = true;
    header('Location: prodinst.php?i=' . $name_prod);
}

?>