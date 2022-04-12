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

//Verificação de data
$data_atual = date('Y-m-d');
$data_limite = new DateTime($data_atual);
$data_user = new DateTime($data);
$intervalo = $data_limite->diff($data_user);
$erro_date = $intervalo->days;
if($erro_date <= 5840){
    $_SESSION['data_incoerente'] = true;
    header('Location: concluir.php');
    exit;
}

//Criptografia da senha
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
    //Contador de Endereço
    $verifica_end = $mysqli->query("SELECT COUNT(cd_endereco) AS endereco FROM tb_endereco");
    $verifica_end = $verifica_end->fetch_assoc();
    $result_end = $verifica_end['endereco'] + 1;
    //Inserir Endereço
    $insert_end = $mysqli->query("INSERT INTO tb_endereco (cd_endereco, sg_uf, nm_cidade, nm_bairro, nm_rua, nm_cep) VALUES ('$result_end', '$uf', '$cidade', '$bairro', '$rua', '$cep')");

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
            $insert = "INSERT INTO tb_usuario (cd_usuario, nm_usuario, nm_senha, nm_email, dt_nascimento, nm_cpf, sg_genero, sg_especialidade, dt_tempo, cd_endereco) VALUES ('$users', '$nome', '$crip', '$email', '$data', '$cpf_string', '$genero', '$especialidade', NOW(), '$result_end')";
            $inserir = $mysqli->query($insert);

            if ($inserir === TRUE){ 
                unset($_SESSION['face_access_token']);
                unset($_SESSION['user_name']);
                unset($_SESSION['user_email']);
                
                $_SESSION['status_cadastro'] = true;
                header('Location: login.php');
                exit;
            }
        }
    }
}

?>