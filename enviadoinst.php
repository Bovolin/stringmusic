<?php
  include ("conexao.php");
  include ("verifica_login.php");

  if(isset($_SESSION['usuario'])){
    $session = $_SESSION['usuario'];

    foreach($mysqli->query("SELECT cd_usuario AS codigo FROM tb_usuario WHERE cd_usuario = '$session'") as $usuarios){
      $codigousuario = $usuarios['codigo'];
    }
  }

  //verificação se há arquivos selecionados (imagens)
  if(isset($_FILES['arquivo'])){

    //pega o arquivo e joga no seu array
    $arquivo = $_FILES['arquivo'];

    if($arquivo['error']){
      $_SESSION['error_stamp'] = true;
      header('Location: adicionarinst.php');
      exit;
    }
 
    //cria o diretório img/
    $pasta = "img/";
    //armazena o nome original da imagem
    $nomeoriginal = $arquivo['name'];
    //cria um nome único para imagem
    $novonome = uniqid();
    //extrai a extensão da imagem
    $extensao = strtolower(pathinfo($nomeoriginal, PATHINFO_EXTENSION));

    //verificação se a extensão é .jpg ou .png
    if($extensao != 'jpg' && $extensao != 'png') die("Tipo de arquivo não aceito! Somente é aceito .jpg e .png");

    //determina o caminho do arquivo
    $path = $pasta . $novonome . "." . $extensao;
    //move o arquivo para o diretório
    $mover = move_uploaded_file($arquivo["tmp_name"], $path);
    
    //se mover = true > executa o sql
    if($mover){
      //contador de imagens
      $sql_select_img = "SELECT COUNT(cd_imagem) as i FROM tb_imagem";
      $sql_select_img = $mysqli->query($sql_select_img);
      $sql_select_img = $sql_select_img->fetch_assoc();
      $result = $sql_select_img['i'] + 1;

      //insere a imagem no banco
      $sql_img = "INSERT INTO tb_imagem (cd_imagem, nm_imagem, path, dt_imagem) VALUES ('$result', '$nomeoriginal', '$path', NOW())"; 
      $query = $mysqli->query($sql_img); 
    
      //pega os atributos do produto pelo método post
      $vnome = $_POST["nome"];
      $vdesc = $_POST["desc"];
      $vprc = str_replace(",", ".", $_POST['prc']);

      //contator de produtos
      $sql_select_prod = "SELECT COUNT(cd_instrumento) as i FROM tb_instrumento";
      $sql_select_prod = $mysqli->query($sql_select_prod);
      $sql_select_prod = $sql_select_prod->fetch_assoc();
      $result_prod = $sql_select_prod['i'] + 1;

      //Verificar se há produtos com esse nome
      $sql_confere_nome = "SELECT COUNT(nm_instrumento) as nome FROM tb_instrumento WHERE nm_instrumento = '$vnome'";
      $sql_confere_nome = $mysqli->query($sql_confere_nome);
      $sql_confere_nome = $sql_confere_nome->fetch_assoc();
      if($sql_confere_nome == 1){
        $_SESSION['produto_existente'] = true;
        header("Location: adicionarinst.php");
        die();
      }
      else{
        //insere o produto no banco
        $sql_prod = "INSERT INTO tb_instrumento (cd_instrumento, nm_instrumento, ds_instrumento, dt_instrumento, vl_instrumento, cd_imagem, cd_usuario, nm_inativo) VALUES ('$result_prod', '$vnome', '$vdesc', NOW(), '$vprc', '$result', '$codigousuario', 0)";
        $query_prod = $mysqli->query($sql_prod);

        //cria sessão só para confirmar se foi postado
        $_SESSION['produtoenviado'] = true;
        
        //redireciona o cliente para a página de produtos
        header("Location: adicionarinst.php");
        die();
      }
    }
    else{
      $_SESSION['produtorecusado'] = true;
      header("Location: instrumentos.php");
      die();
    }
    
  } 
  else{
    $_SESSION['produtorecusado'] = true;
    header("Location: instrumentos.php");
    die();
  }
?>
