<?php
session_start();
include ("conexao.php");

$preview_user = $_GET['u'];

if(isset($_SESSION['usuario'])){
    $session = $_SESSION['usuario'];
  
    foreach($mysqli->query("SELECT cd_imagem AS confere FROM tb_usuario WHERE cd_usuario = '$session'") as $usuarionav){
        $semfoto_conta = $usuarionav['confere'];
    }
  
    if(empty($semfoto_conta)){
        foreach($mysqli->query("SELECT u.nm_usuario AS nome, u.sg_especialidade AS especialidade FROM tb_usuario AS u WHERE cd_usuario = '$session'") as $conta_propria){
            $nomeusuario_conta = $conta_propria['nome'];
            $imagemusuario_conta = "imgs/user.png";
            if($conta_propria['especialidade'] == "m") $especialidadeusuario_conta = "Músico";
            elseif($conta_propria['especialidade'] == "c") $especialidadeusuario_conta = "Compositor";
            elseif($conta_propria['especialidade'] == "v") $especialidadeusuario_conta = "Visitante";      
        }
    }
    else{
        foreach($mysqli->query("SELECT u.nm_usuario AS nome, u.sg_especialidade AS especialidade, i.path AS path FROM tb_usuario AS u JOIN tb_imagem AS i ON i.cd_imagem = u.cd_imagem WHERE cd_usuario = '$session'") as $conta_propria){
            $nomeusuario_conta = $conta_propria['nome'];
            $imagemusuario_conta = $conta_propria['path'];
            if($conta_propria['especialidade'] == "m") $especialidadeusuario_conta = "Músico";
            elseif($conta_propria['especialidade'] == "c") $especialidadeusuario_conta = "Compositor";
            elseif($conta_propria['especialidade'] == "v") $especialidadeusuario_conta = "Visitante";      
        }
    }
}

foreach($mysqli->query("SELECT cd_imagem AS confere FROM tb_usuario WHERE cd_usuario = '$preview_user'") as $conferefoto){
  $semfoto = $conferefoto['confere'];
}

if(empty($semfoto)){
  foreach($mysqli->query(
  "SELECT us.nm_usuario AS nome, 
  us.nm_email AS email, 
  us.sg_especialidade AS especialidade, 
  us.ds_usuario AS descricao, 
  DATE_FORMAT(us.dt_tempo, '%m/%Y') AS tempo,
  u.sg_uf AS uf,
  c.nm_cidade AS cidade
    FROM tb_usuario AS us JOIN tb_uf AS u
        ON u.cd_uf = us.cd_uf
            JOIN tb_cidade AS c
                ON c.cd_cidade = us.cd_cidade
                    WHERE us.cd_usuario = '$preview_user'") as $usuarios){
  $nomeusuario = $usuarios['nome'];
  $descricaousuario = $usuarios['descricao'];
  $emailusuario = $usuarios['email'];
  $tempousuario = $usuarios['tempo'];
  $cidadeusuario = $usuarios['cidade'];
  $imgusuario = "imgs/user.png";

  if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
  elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
  elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";
  
  if($usuarios['uf'] == "SP") $ufusuario = "São Paulo";
  }
}
else{
  foreach($mysqli->query(
    "SELECT us.nm_usuario AS nome, 
    us.nm_email AS email, 
    us.sg_especialidade AS especialidade, 
    us.ds_usuario AS descricao, 
    DATE_FORMAT(us.dt_tempo, '%m/%Y') AS nascimento,
    u.sg_uf AS uf,
    i.path AS path,
    c.nm_cidade AS cidade
        FROM tb_usuario AS us JOIN tb_uf AS u
            ON u.cd_uf = us.cd_uf
                JOIN tb_imagem AS i
                    ON i.cd_imagem = us.cd_imagem
                        JOIN tb_cidade AS c
                            ON c.cd_cidade = us.cd_cidade
                                WHERE us.cd_usuario = '$preview_user'") as $usuarios){
    $nomeusuario = $usuarios['nome'];
    $descricaousuario = $usuarios['descricao'];
    $emailusuario = $usuarios['email'];
    $tempousuario = $usuarios['nascimento'];
    $imgusuario = $usuarios['path'];
    $cidadeusuario = $usuarios['cidade'];
  
    if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
    elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
    elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
    }

    if($usuarios['uf'] == "SP") $ufusuario = "São Paulo";
}



?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/styleusuarios.css" />
        <script src="swal.js"></script>
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
        <title>StringMusic</title>
    </head>

    <body>

    <nav class="container-fluid nav">
        <div class="container cf" style="background<?php echo $imgfundo ?>">
          <div class="brand" >
            <a href="index.php"><img src="logo/roxo_preto.png" alt="Logo" style="width: 450px;"></a>
          </div>
          <i class="fa fa-bars nav-toggle"></i>
          <ul class="un-navbar">
            <li class="navbar"><a href="index.php">Inicio</a></li>
            <li class="navbar"><a href="loja.php">Produtos</a></li>
            <li class="navbar"><a href="servico.php">Serviços</a></li>
            <?php
            if(isset($_SESSION['usuario'])){
              echo
              '<li class="navbar">
                <div class="action">
                  <div class="profile" onclick="menuAlterna();">
                      <img src="'; echo $imgusuario; echo'">
                  </div>
                  <div class="menu">
                    <h3 style="margin-bottom: 0px;">'; echo $nomeusuario; echo '<br><span>'; echo $especialidadeusuario; echo '</span></h3>
                      <ul class="un">
                        <li class="info"><i class="bx bx-user-circle"></i><a href="painel.php">Meu Perfil</a></li>
                        <li class="info"><i class="bx bxs-user-detail"></i><a href="editarperfil.php">Editar Perfil</a></li>
                        <li class="info"><i class="bx bx-envelope"></i><a href="meusprodutos.php">Meus Produtos</a></li>
                        <li class="sair"><i class="bx bx-log-out"></i><a href="logout.php">Sair</a></li>
                        <li class="info"><input type="checkbox" name="switch-theme" id="switch">
                        <label for="switch" class="toggle">Toggle</label>
                        <script src="script_dark.js"></script></li>
                      </ul>
                  </div>
                </div>
              </li>';
            }
            else{
              echo '<li class="navbar"><a href="login.php">Login</a></li>';
            }
            ?>
          </ul>
        </div>
      </nav>

        <section class="section-perfil-usuario">
            <div class="perfil-usuario-fundo">
                <div class="perfil-usuario-portal">
                    <div class="perfil-usuario-avatar">
                        <img src="<?php echo $imgusuario ?>" alt="">
                    </div>
                </div>
            </div>
            <div class="perfil-usuario-body">
                <div class="perfil-usuario-descricao">
                    <h3 class="titulo"><?php echo $nomeusuario?></h3>
                    <p class="texto"><?php echo $descricaousuario?></p> 
                </div>
                <div class="perfil-usuario-footer">
                    <ul class="dados">
                        <li><i class="icono fas fa-map-marked-alt" aria-hidden="true"> Localização: </i></li>
                        <h4><?php echo $cidadeusuario . ', ' . $ufusuario?></h4>
                        <li><i class="icono fas fa-envelope"> Email para contato: </i></li>
                        <h4><?php echo $emailusuario?></h4>
                        <li><i class="icono fa fa-calendar" aria-hidden="true"> No site desde: </i></li>
                        <h4><?php echo $tempousuario?></h4>
                        <li><i class="icono fas fa-music"> Tipo de usuário: </i></li>
                        <h4><?php echo $especialidadeusuario?></h4>
                    </ul>
                </div>
                <div class="redes-sociais">
                    <a href="" class="boton-redes facebook fab fa-facebook-f"><i class="icon-facebook"></i></a>
                    <a href="" class="boton-redes instagram fab fa-instagram"><i class="icon-instagram"></i></a>
                </div>
            </div>
        </section>

        <br>

        <div class="container" style="margin-left: 10%;">
        <?php
        $busca_prod = "SELECT COUNT(s.cd_interpretacao) AS codigo FROM tb_interpretacao AS s JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$preview_user'";
        $busca_prod = $mysqli->query($busca_prod);
        $busca_prod = $busca_prod->fetch_assoc();
        $confirma_prod = $busca_prod['codigo'];
        if($confirma_prod == 0){
            echo '<h2 style="color: var(--color-headings)>O usuário não possui produtos!</h2>';
        }
        else{
        ?>
        <div class="prod">
            <h2 style="color: var(--color-headings)">Produtos</h2>
            <main class="grid">

            <?php

                $meusprods = "SELECT s.cd_interpretacao, s.nm_interpretacao, s.ds_interpretacao, s.vl_interpretacao, s.qt_interpretacao, i.path FROM tb_interpretacao AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$preview_user'";
                $query = $mysqli->query($meusprods);

                while($dados = $query->fetch_array()){
                    echo 
                    '<article>
                        <img src="'; echo $dados['path']; echo '" alt="" style="width: 130px; height: 175px;">
                        <div class="text">
                            <h3>'; echo $dados['nm_interpretacao']; echo '</h3>
                            <p>'; echo $dados['ds_interpretacao']; echo '</p>
                            <p>R$'; echo $dados['vl_interpretacao']; echo '</p>
                            <p>Quantidade: '; echo $dados['qt_interpretacao']; echo '</p>
                            <form method="get" action="produto.php">
                              <input type="text" name="p" style="display: none;" value="'; echo $dados['cd_interpretacao']; echo '">
                              <input type="submit" class="btnpart" value="Comprar">
                            </form>
                        </div>
                    </article>';
                }

            ?>
 
            </main>
        </div>
        <?php }
        $busca_serv = "SELECT COUNT(s.cd_servico) AS codigoserv FROM tb_servico AS s JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$preview_user'";
        $busca_serv = $mysqli->query($busca_serv);
        $busca_serv = $busca_serv->fetch_assoc();
        $confirma_serv = $busca_serv['codigoserv'];
        if($confirma_serv == 0){
            echo '<h2 style="color: var(--color-headings)">O usuário não possui serviços!</h2>';
        }
        else{
        ?>
        <div class="serv">
            <h2 style="color: var(--color-headings)">Serviços</h2>
            <main class="grid">
                <?php

                    $meusserv = "SELECT s.cd_servico, s.nm_servico, s.ds_servico, s.vl_servico, i.path FROM tb_servico AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario as u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$preview_user'";
                    $queryserv = $mysqli->query($meusserv);

                    while($dados = $queryserv->fetch_array()){
                        echo
                        '<article>
                            <img src="'; echo $dados['path']; echo '" alt="" style="width: 130px; height: 175px;">
                            <div class="text">
                                <h3>'; echo $dados['nm_servico']; echo '</h3>
                                <p>'; echo $dados['ds_servico']; echo '</p>
                                <p>R$'; echo $dados['vl_servico']; echo '</p>
                                <form method="get" action="prodserv.php">
                                  <input type="text" name="s" style="display: none;" value="'; echo $dados['cd_servico']; echo '">
                                  <input type="submit" class="btnpart" value="Comprar">
                                </form>
                            </div>
                        </article>';
                    }

                ?>
            </main>
        </div>
        <?php } ?>
    </div>  
  </body>
</html>