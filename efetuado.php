<?php

session_start();

include ("conexao.php");

//Nota de esclarecimento: "mysqli_real_escape_string" = evita SQL Injection
//Pegar credenciais por POST
$nome = mysqli_real_escape_string($mysqli, $_POST['nome']);
$email = mysqli_real_escape_string($mysqli, $_POST['email']);
$senha = mysqli_real_escape_string($mysqli, trim($_POST['senha']));
$cpf = mysqli_real_escape_string($mysqli, $_POST['cpf']);
$data = mysqli_real_escape_string($mysqli, $_POST['data']);
$cep = mysqli_real_escape_string($mysqli, $_POST['cep']);
$uf = mysqli_real_escape_string($mysqli, $_POST['uf']);
$cidade = mysqli_real_escape_string($mysqli, $_POST['cidade']);
$rua = mysqli_real_escape_string($mysqli, $_POST['rua']);
$bairro = mysqli_real_escape_string($mysqli, $_POST['bairro']);
$genero = mysqli_real_escape_string($mysqli, $_POST['genero']);
$especialidade = mysqli_real_escape_string($mysqli, $_POST['especialidade']);

$frase_array = str_split(str_replace(' ', '',$senha));
$frase_count = strlen(str_replace(' ', '',$senha));
$j = 2;
$crip = 0;

for($i = 0; $i < $frase_count; $i++){
    $conta = pow($j, ord($frase_array[$i]) + 12);
    $j +=  20;
    $crip += $conta;
}

//Validação pelo Select
$result = "SELECT COUNT(*) AS total FROM tb_usuario WHERE nm_email = '$email'";
$result = $mysqli->query($result);
$result = $result->fetch_assoc();
if($result['total'] == 1){
    $_SESSION['usuario_existe'] = true;
    header('Location: concluir.php');
    exit;
}
else{
    //Verificação do Estado
    $verifica_uf = "SELECT COUNT(sg_uf) AS verificado FROM tb_uf WHERE sg_uf = '$uf'";
    $verifica_uf = $mysqli->query($verifica_uf);
    $verifica_uf = $verifica_uf->fetch_assoc();
    //Se já tiver um Estado no banco --> utilizar o código dele
    if($verifica_uf['verificado'] == 1){
        //Selecionando o Estado
        foreach($mysqli->query("SELECT cd_uf FROM tb_uf WHERE sg_uf = '$uf'") as $rowuf){
            $ufvalidado = $rowuf["cd_uf"];
        }   
    }
    //Se não --> cadastrar o Estado
    else{
        //Contador de Estado
        $contador_uf = "SELECT COUNT(cd_uf) AS codigo FROM tb_uf WHERE sg_uf = '$uf'";
        $contador_uf = $mysqli->query($contador_uf);
        $contador_uf = $contador_uf->fetch_assoc();
        $estado = $contador_uf['codigo'] + 1;
        //Inserção do Estado
        $insert_uf = "INSERT INTO tb_uf (cd_uf, sg_uf) VALUES ('$estado', '$uf')";
        $query_insert_uf = $mysqli->query($insert_uf);
        //Seleciona o Estado novamente para cadastro
        foreach($mysqli->query("SELECT cd_uf FROM tb_uf WHERE sg_uf = '$uf'") as $rowuf){
            $ufvalidado = $rowuf["cd_uf"];
        }
    }

    //Verificação de Cidade
    $verifica_cidade = "SELECT COUNT(cd_cidade) AS cidade FROM tb_cidade WHERE nm_cidade = '$cidade'";
    $verifica_cidade = $mysqli->query($verifica_cidade);
    $verifica_cidade = $verifica_cidade->fetch_assoc();
    //Se já tiver um Cidade no banco --> utilizar o código dele
    if($verifica_cidade['cidade'] == 1){
        //Selecionando a Cidade
        foreach($mysqli->query("SELECT cd_cidade FROM tb_cidade WHERE nm_cidade = '$cidade'") as $rowcidade){
            $cidadevalidada = $rowcidade["cd_cidade"];
        }
    }
    //Se não --> insere a Cidade
    else{
        //Contador de Cidade
        $contador_cidade = "SELECT COUNT(cd_cidade) AS codigo FROM tb_cidade";
        $contador_cidade = $mysqli->query($contador_cidade);
        $contador_cidade = $contador_cidade->fetch_assoc();
        $city = $contador_cidade['codigo'] + 1;
        //Inserção da Cidade
        $insert_cidade = "INSERT INTO tb_cidade (cd_cidade, nm_cidade) VALUES ('$city', '$cidade')";
        $query_insert_cidade = $mysqli->query($insert_cidade);
        //Seleção da Cidade inserida
        foreach($mysqli->query("SELECT cd_cidade FROM tb_cidade WHERE nm_cidade = '$cidade'") as $rowcidade){
            $cidadevalidada = $rowcidade["cd_cidade"];
        }
    }

    //Verificação de Bairro
    $verifica_bairro = "SELECT COUNT(cd_bairro) AS bairro FROM tb_bairro WHERE nm_bairro = '$bairro'";
    $verifica_bairro = $mysqli->query($verifica_bairro);
    $verifica_bairro = $verifica_bairro->fetch_assoc();
    //Se já tiver um Bairro no banco --> utilizar código dele
    if($verifica_bairro['bairro'] == 1){
        //Selecionando o Bairro
        foreach($mysqli->query("SELECT cd_bairro FROM tb_bairro WHERE nm_bairro = '$bairro'") as $rowbairro){
            $bairrovalidado = $rowbairro["cd_bairro"];
        }
    }
    //Se não --> Insere o bairro
    else{
        //Contador de Bairro
        $contador_bairro = "SELECT COUNT(cd_bairro) AS codigo FROM tb_bairro";
        $contador_bairro = $mysqli->query($contador_bairro);
        $contador_bairro = $contador_bairro->fetch_assoc();
        $distric = $contador_bairro['codigo'] + 1;
        //Insere o Bairro
        $insert_bairro = "INSERT INTO tb_bairro (cd_bairro, nm_bairro) VALUES ('$distric', '$bairro')";
        $query_insert_bairro = $mysqli->query($insert_bairro);
        //Selecionando o Bairro
        foreach($mysqli->query("SELECT cd_bairro FROM tb_bairro WHERE nm_bairro = '$bairro'") as $rowbairro){
            $bairrovalidado = $rowbairro["cd_bairro"];
        }
    }

    //Verificar CPF válido
    $cpf_string = strval($cpf);
    $cpf_string = preg_replace("/[^0-9]/", "", $cpf_string);
    $digitoUm = 0;
    $digitoDois = 0;

    for($i = 0, $x = 10; $i <= 8, $x >= 2; $i++, $x--){
        $digitoUm += $cpf_string[$i] * $x;
    }
    for($i = 0, $x = 11; $i <= 9, $x >= 2; $i++, $x--){
        if(str_repeat($i, 11) == $cpf_string){
            $_SESSION['cpf_invalido'] = true;
            header('Location: concluir.php');
            exit;
        }
        $digitoDois += $cpf_string[$i] * $x;
    }

    $calculadoUm = (($digitoUm % 11) < 2) ? 0 : 11 - ($digitoUm % 11);
    $calculdoDois = (($digitoDois % 11) < 2) ? 0 : 11 - ($digitoDois % 11);
    if($calculadoUm <> $cpf_string[9] || $calculdoDois <> $cpf_string[10]){
            $_SESSION['cpf_invalido'] = true;
            header('Location: concluir.php');
            exit;
    }
    else{
        //Verificar se o CPF já foi cadastro
        $verifica_cpf = $mysqli->query("SELECT COUNT(nm_cpf) AS verifica_cpf FROM tb_usuario WHERE nm_cpf = '$cpf_string'");
        $verifica_cpf = $verifica_cpf->fetch_assoc();
        if($verifica_cpf['verifica_cpf'] == 1){
            $_SESSION['cpf_cadastrado'] = true;
            header('Location: concluir.php');
            exit;
        }
        else{
            //Contador de usuários
            $select_user = "SELECT COUNT(cd_usuario) AS codigo FROM tb_usuario";
            $select_user = $mysqli->query($select_user);
            $select_user = $select_user->fetch_assoc();
            $users = $select_user['codigo'] + 1;

            //Inserção do cadastro
            $insert = "INSERT INTO tb_usuario (cd_usuario, nm_usuario, nm_senha, nm_email, dt_nascimento, nm_cpf, sg_genero, nm_cep, nm_endereco, sg_especialidade, dt_tempo, cd_cidade, cd_uf, cd_bairro) VALUES ('$users', '$nome', '$crip', '$email', '$data', '$cpf_string', '$genero', '$cep', '$rua', '$especialidade', NOW(), '$cidadevalidada', '$ufvalidado', '$bairrovalidado')";
            $inserir = $mysqli->query($insert);

            if ($inserir === TRUE){ 
                $_SESSION['status_cadastro'] = true;
                header('Location: login.php');
                exit;
            }
        }
    }
}

?>