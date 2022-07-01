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
      header('Location: adicionarprod.php');
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
      $vnome = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST["nome"]));
      $vdesc = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST["desc"]));
      $vtipo = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST["select"]));
      $vprc = str_replace(",", ".", $_POST['prc']);
      $genero_musical = $_POST['genero_musical'];
      if($genero_musical == ""){
        $_SESSION['selecione_genero'];
        header("Location: adicionarprod.php");
        exit;
      }

      //contator de produtos
      $sql_select_prod = "SELECT COUNT(cd_interpretacao) as i FROM tb_interpretacao";
      $sql_select_prod = $mysqli->query($sql_select_prod);
      $sql_select_prod = $sql_select_prod->fetch_assoc();
      $result_prod = $sql_select_prod['i'] + 1;

      //Verificar se há produtos com esse nome
      $sql_confere_nome = $mysqli->query("SELECT COUNT(nm_interpretacao) as nome FROM tb_interpretacao WHERE nm_interpretacao = '$vnome'");
      $sql_confere_nome = $sql_confere_nome->fetch_assoc();
      if($sql_confere_nome['nome'] == 1){
        $_SESSION['produto_existente'] = true;
        header("Location: adicionarprod.php");
        die();
      }
      else{
        if($vtipo == "v"){
          if(!isset($_FILES['arquivo_pdf'])){
            $_SESSION['sem_pdf'] = true;
            header("Location: adicionarprod.php");
            die();
          }
          else{
            $arquivo_pdf = $_FILES['arquivo_pdf'];

            if($arquivo_pdf['error']){
              $_SESSION['error_stamp_pdf'] = true;
              header('Location: adicionarprod.php');
              exit;
            }

            $pasta_pdf = "arq_pdf/";
            $nomeoriginal_pdf = $arquivo_pdf['name'];
            $novonome_pdf = uniqid();
            $extensao_pdf = strtolower(pathinfo($nomeoriginal_pdf, PATHINFO_EXTENSION));
            $path_pdf = $pasta_pdf . $novonome_pdf . "." . $extensao_pdf;
            $mover_pdf = move_uploaded_file($arquivo_pdf["tmp_name"], $path_pdf);

            if($mover_pdf){

              //contador de imagens
              $sql_select_pdf = $mysqli->query("SELECT COUNT(cd_arquivo) as a FROM tb_arquivo");
              $sql_select_pdf = $sql_select_pdf->fetch_assoc();
              $result_pdf = $sql_select_pdf['a'] + 1;

              $query_pdf = $mysqli->query("INSERT INTO tb_arquivo (cd_arquivo, nm_arquivo, nm_path, dt_arquivo) VALUES ('$result_pdf', '$nomeoriginal_pdf', '$path_pdf', NOW())"); 

              //insere o produto no banco
              $sql_prod = "INSERT INTO tb_interpretacao (cd_interpretacao, nm_interpretacao, ds_interpretacao, dt_interpretacao, vl_interpretacao, cd_imagem, cd_usuario, nm_inativo, nm_genero, sg_tipo, cd_arquivo) VALUES ('$result_prod', '$vnome', '$vdesc', NOW(), '$vprc', '$result', '$codigousuario', 0, '$genero_musical', '$vtipo', '$result_pdf')";
              $query_prod = $mysqli->query($sql_prod);

              //cria sessão só para confirmar se foi postado
              $_SESSION['produtoenviado'] = true;
              
              //redireciona o cliente para a página de produtos
              header("Location: adicionarprod.php");
              die();
            }
            else{
              $_SESSION['error_mover'] = true;
              header("Location: adicionarprod.php");
              die();
            }
          }
        }
        else{
          //insere o produto no banco
          $sql_prod = "INSERT INTO tb_interpretacao (cd_interpretacao, nm_interpretacao, ds_interpretacao, dt_interpretacao, vl_interpretacao, cd_imagem, cd_usuario, nm_inativo, nm_genero, sg_tipo) VALUES ('$result_prod', '$vnome', '$vdesc', NOW(), '$vprc', '$result', '$codigousuario', 0, '$genero_musical', '$vtipo')";
          $query_prod = $mysqli->query($sql_prod);

          //cria sessão só para confirmar se foi postado
          $_SESSION['produtoenviado'] = true;
          
          //redireciona o cliente para a página de produtos
          header("Location: adicionarprod.php");
          die();
        }
      }
    }
    else{
      $_SESSION['produtorecusado'] = true;
      header("Location: interpretacoes.php");
      die();
    }
    
  } 
  else{
    $_SESSION['produtorecusado'] = true;
    header("Location: interpretacoes.php");
    die();
  }
?>
