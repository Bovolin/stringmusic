<?php
include ("conexao.php");
include ("verifica_login.php");

//Utilizar codigo do usuário da sessão -> a $_SESSION['usuario'] já armazena o código
$codigo_usuario = $_SESSION['usuario'];
//Pegar credenciais por POST
$descricao = $_POST['descricao'];
$cep = $_POST['cep'];
$cidade = $_POST['cidade'];
$uf = $_POST['uf'];
$bairro = $_POST['bairro'];
$rua = $_POST['rua'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$confirma_senha = $_POST['confirma_senha'];

if($senha == $confirma_senha){
    $frase_array = str_split(str_replace(' ', '',$senha));
    $frase_count = strlen(str_replace(' ', '',$senha));
    $j = 2;
    $crip = 0;

    for($i = 0; $i < $frase_count; $i++){
        $conta = pow($j, ord($frase_array[$i]) + 12);
        $j +=  20;
        $crip += $conta;
    }
    $confere_senha = $mysqli->query("SELECT nm_senha FROM tb_usuario WHERE nm_senha = '$crip' AND cd_usuario = '$codigo_usuario'");
    $row = mysqli_num_rows($confere_senha);
    if($row == 1){
        //Pegar endereço
        foreach($mysqli->query("SELECT cd_endereco AS endereco FROM tb_usuario WHERE cd_usuario = '$codigo_usuario'") as $pega_endereco){
            $codigo_endereco = $pega_endereco['endereco'];
        }
        //Update descrição
        $upd_desc = $mysqli->query("UPDATE tb_usuario SET ds_usuario = '$descricao' WHERE cd_usuario = '$codigo_usuario'");
     
        $upd_endereco = $mysqli->query("UPDATE tb_endereco 
                                            SET sg_uf = '$uf',
                                                nm_cidade = '$cidade',
                                                nm_bairro = '$bairro',
                                                nm_rua = '$rua',
                                                nm_cep = '$cep'
                                                WHERE cd_endereco = '$codigo_endereco'");

        $_SESSION['cred_alterada'] = true;
        header('Location: painel.php');
        exit;
    }
    else{
        $_SESSION['senha_incorreta'] = true;
        header('Location: editarperfil.php');
        exit;
    }
}
else{
    $_SESSION['dupla_senha'] = true;
    header('Location: editarperfil.php');
    exit;
}



?>