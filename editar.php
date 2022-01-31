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
    $confere_senha = $mysqli->query("SELECT nm_senha FROM tb_usuario WHERE nm_senha = md5('$senha') AND cd_usuario = '$codigo_usuario'");
    $row = mysqli_num_rows($confere_senha);
    if($row == 1){
        //Update descrição
        $upd_desc = $mysqli->query("UPDATE tb_usuario SET ds_usuario = '$descricao' WHERE cd_usuario = '$codigo_usuario'");

        //Verificar cidade, bairro e uf no banco
        //UF
        foreach($mysqli->query("SELECT sg_uf AS uf_comparado FROM tb_uf WHERE sg_uf = '$uf'") as $compara_uf){
            $uf_tbuf = $compara_uf['uf_comparado'];
            //Se for igual --> mantém
            if($uf == $uf_tbuf){
                $uf_valido = $uf;
            }
            //Se não for igual --> adiciona e seleciona
            else{
                //Contador de uf
                $contador_uf = $mysqli->query("SELECT COUNT(cd_uf) AS codigo_uf FROM tb_uf");
                $contador_uf = $contador_uf->fetch_assoc();
                $codigo_uf = $contador_uf['codigo_uf'] + 1;
                //Insere uf
                $mysqli->query("INSERT INTO tb_uf (cd_uf, sg_uf) VALUES ('$codigo_uf', '$uf')");
                //Seleciona uf
                foreach($mysqli->query("SELECT cd_uf AS uf_novo FROM tb_uf WHERE sg_uf = '$uf'") as $pega_uf){
                    $uf_valido = $pega_uf['uf_novo'];
                }
            }
        }
        //CIDADE
        foreach($mysqli->query("SELECT nm_cidade AS cidade_comparada FROM tb_cidade WHERE nm_cidade = '$cidade'") as $compara_cidade){
            $cidade_tbcidade = $compara_cidade['cidade_comparada'];
            //Se for igual --> mantém
            if($cidade == $cidade_tbcidade){
                $cidade_valida = $cidade;
            }
            //Se não for igual --> adiciona e seleciona
            else{
                //Contador de cidade
                $contador_cidade = $mysqli->query("SELECT COUNT(cd_cidade) AS codigo_cidade FROM tb_cidade");
                $contador_cidade = $contador_cidade->fetch_assoc();
                $codigo_cidade = $contador_cidade['codigo_cidade'] + 1;
                //Insere cidade
                $mysqli->query("INSERT INTO tb_cidade (cd_cidade, nm_cidade) VALUES ('$codigo_cidade', '$cidade')");
                //Seleciona cidade
                foreach($mysqli->query("SELECT cd_cidade AS cidade_nova FROM tb_cidade WHERE nm_cidade = '$cidade'") as $pega_cidade){
                    $cidade_valida = $pega_cidade['cidade_nova'];
                }
            }
        }
        //BAIRRO
        foreach($mysqli->query("SELECT nm_bairro AS bairro_comparado FROM tb_bairro WHERE nm_bairro = '$bairro'") as $compara_bairro){
            $bairro_tbbairro = $compara_bairro['bairro_comparado'];
            //Se for igual --> mantém
            if($bairro == $bairro_tbbairro){
                $bairro_valido = $bairro;
            }
            //Se não for igual --> adiciona e seleciona
            else{
                //Contador de bairro
                $contador_bairro = $mysqli->query("SELECT COUNT(cd_bairro) AS codigo_bairro FROM tb_bairro");
                $contador_bairro = $contador_bairro->fetch_assoc();
                $codigo_bairro = $contador_bairro['codigo_bairro'] + 1;
                //Insere bairro
                $mysqli->query("INSERT INTO tb_bairro (cd_bairro, nm_bairro) VALUES ('$codigo_bairro', '$bairro')");
                //Seleciona bairro
                foreach($mysqli->query("SELECT cd_bairro AS bairro_novo FROM tb_bairro WHERE nm_bairro = '$bairro'") as $pega_bairro){
                    $bairro_valido = $pega_bairro['bairro_novo'];
                }
            }
        }

        //Update na tabela do usuario
        $upd_bairro = $mysqli->query("UPDATE tb_usuario SET cd_bairro = '$bairro_valido' WHERE cd_usuario = '$codigo_usuario'");
        $upd_uf = $mysqli->query("UPDATE tb_usuario SET cd_uf = '$uf_valido' WHERE cd_usuario = '$codigo_usuario'");
        $upd_cidade = $mysqli->query("UPDATE tb_usuario SET cd_cidade = '$cidade_valida' WHERE cd_usuario = '$codigo_usuario'");
        $upd_email = $mysqli->query("UPDATE tb_usuario SET nm_email = '$email' WHERE cd_usuario = '$codigo_usuario'");
        $upd_cep = $mysqli->query("UPDATE tb_usuario SET nm_cep = '$cep' WHERE cd_usuario = '$codigo_usuario'");
        $upd_rua = $mysqli->query("UPDATE tb_usuario SET nm_endereco = '$rua' WHERE cd_usuario = '$codigo_usuario'");

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