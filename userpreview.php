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
            $imagemusuario_conta = "imgs/user.jpeg";
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
foreach($mysqli->query("SELECT cd_fundo AS confere_fundo FROM tb_usuario WHERE cd_usuario = '$preview_user'") as $conferefundo){
  $semfundo = $conferefundo['confere_fundo'];
}

  if(empty($semfoto) && empty($semfundo)){
    foreach($mysqli->query(
    "SELECT us.nm_usuario AS nome, 
    us.nm_email AS email, 
    us.sg_especialidade AS especialidade,
    us.ds_usuario AS descricao, 
    DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
    e.sg_uf AS uf,
    e.nm_cidade AS cidade
    FROM tb_usuario AS us
      JOIN tb_endereco AS e
        ON e.cd_endereco = us.cd_endereco
          WHERE us.cd_usuario = '$preview_user'") as $usuarios){
    $nomeusuario = $usuarios['nome'];
    $descricaousuario = $usuarios['descricao'];
    $emailusuario = $usuarios['email'];
    $nascimentousuario = $usuarios['nascimento'];
    $ufusuario = $usuarios['uf'];
    $cidadeusuario = $usuarios['cidade'];
    $imgusuario = "imgs/user.jpeg";
    $imgfundo = ": linear-gradient(45deg, #BC3CFF, #317FFF);";

    if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
    elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
    elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
    }
  }
  elseif (empty($semfoto)){
    foreach($mysqli->query(
      "SELECT us.nm_usuario AS nome, 
      us.nm_email AS email, 
      us.sg_especialidade AS especialidade,
      us.ds_usuario AS descricao, 
      DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
      e.sg_uf AS uf,
      e.nm_cidade AS cidade,
      (SELECT im.path FROM tb_fundo as f 
      	JOIN tb_imagem as im
      	  ON im.cd_imagem = f.cd_imagem
       		JOIN tb_usuario as us
       		  ON us.cd_fundo = f.cd_fundo
      			WHERE us.cd_usuario = '$preview_user') AS fundo
        FROM tb_usuario AS us
          JOIN tb_endereco AS e
            ON e.cd_endereco = us.cd_endereco
                WHERE us.cd_usuario = '$preview_user'") as $usuarios){
      $nomeusuario = $usuarios['nome'];
      $descricaousuario = $usuarios['descricao'];
      $emailusuario = $usuarios['email'];
      $nascimentousuario = $usuarios['nascimento'];
      $ufusuario = $usuarios['uf'];
      $cidadeusuario = $usuarios['cidade'];
      $imgusuario = "imgs/user.jpeg";
      $imgfundo = "-image: url(" . $usuarios['fundo'] . ")";
  
      if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
      elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
      elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
      }
  }
  elseif(empty($semfundo)){
    foreach($mysqli->query(
      "SELECT us.nm_usuario AS nome, 
      us.nm_email AS email, 
      us.sg_especialidade AS especialidade, 
      us.ds_usuario AS descricao, 
      DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
      e.sg_uf AS uf,
      e.nm_cidade AS cidade,
      i.path AS path
        FROM tb_usuario AS us
          JOIN tb_endereco AS e
            ON e.cd_endereco = us.cd_endereco
              JOIN tb_imagem AS i
              ON i.cd_imagem = us.cd_imagem
                WHERE us.cd_usuario = '$preview_user'") as $usuarios){
      $nomeusuario = $usuarios['nome'];
      $descricaousuario = $usuarios['descricao'];
      $emailusuario = $usuarios['email'];
      $nascimentousuario = $usuarios['nascimento'];
      $ufusuario = $usuarios['uf']; 
      $cidadeusuario = $usuarios['cidade'];
      $imgusuario = $usuarios['path'];
      $imgfundo = ": linear-gradient(45deg, #BC3CFF, #317FFF);";

      if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
      elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
      elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
      }
  }
  else{
    foreach($mysqli->query(
      "SELECT us.nm_usuario AS nome, 
      us.nm_email AS email, 
      us.sg_especialidade AS especialidade, 
      us.ds_usuario AS descricao, 
      DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
      e.sg_uf AS uf,
      e.nm_cidade AS cidade,
      i.path AS path,
      (SELECT im.path FROM tb_fundo as f 
      	JOIN tb_imagem as im
      	  ON im.cd_imagem = f.cd_imagem
       		JOIN tb_usuario as us
       		  ON us.cd_fundo = f.cd_fundo
      			WHERE us.cd_usuario = '$preview_user') AS fundo
        FROM tb_usuario AS us
          JOIN tb_endereco AS e
            ON e.cd_endereco = us.cd_endereco 
              JOIN tb_imagem AS i
                ON i.cd_imagem = us.cd_imagem
                  WHERE us.cd_usuario = '$preview_user'") as $usuarios){
      $nomeusuario = $usuarios['nome'];
      $descricaousuario = $usuarios['descricao'];
      $emailusuario = $usuarios['email'];
      $nascimentousuario = $usuarios['nascimento'];
      $ufusuario = $usuarios['uf']; 
      $cidadeusuario = $usuarios['cidade'];
      $imgusuario = $usuarios['path'];
      $imgfundo = "-image: url(" . $usuarios['fundo'] . ")";

      if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
      elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
      elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
    }
}



?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/styleusuarios.css" />
        <script src="js/swal.js"></script>
        <script src="js/script.js"></script>
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
          google.charts.load('current', {'packages':['bar']});
          google.charts.setOnLoadCallback(drawChart);

          function drawChart(){
            var data = google.visualization.arrayToDataTable([
              ['Produtos', 'Interpretações', 'Serviços', 'Instrumentos', 'Total'],

              <?php
                //Vendas do usuário
                $vendas = $mysqli->query(
                  "SELECT count(i.cd_interpretacao) as 'Interpretações',
                    (select count(s.cd_servico)
                      from tb_servico as s
                        join tb_usuario as u
                                on u.cd_usuario = s.cd_usuario
                                      join tb_carrinho as c
                                        on s.cd_servico = c.cd_servico
                                          join tb_compra as co
                                          on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$preview_user') as 'Serviços',
                    (select count(ins.cd_instrumento) 
                        from tb_instrumento as ins
                            join tb_usuario as u
                                on u.cd_usuario = ins.cd_usuario
                                    join tb_carrinho as c
                                        on ins.cd_instrumento = c.cd_instrumento
                                          join tb_compra as co
                                            on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$preview_user') as 'Instrumentos',
                    count(i.cd_interpretacao) + (select count(s.cd_servico)
                      from tb_servico as s
                        join tb_usuario as u
                                on u.cd_usuario = s.cd_usuario
                                      join tb_carrinho as c
                                        on s.cd_servico = c.cd_servico
                                          join tb_compra as co
                                          on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$preview_user') + (select count(ins.cd_instrumento) 
                          from tb_instrumento as ins
                              join tb_usuario as u
                                  on u.cd_usuario = ins.cd_usuario
                                      join tb_carrinho as c
                                          on ins.cd_instrumento = c.cd_instrumento
                                            join tb_compra as co
                                              on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$preview_user') as 'Total'
                      from tb_interpretacao as i
                          join tb_usuario as u
                              on u.cd_usuario = i.cd_usuario
                                  join tb_carrinho as c
                                      on i.cd_interpretacao = c.cd_interpretacao
                                        join tb_compra as co
                                              on c.cd_carrinho = co.cd_carrinho
                                                where u.cd_usuario = '$preview_user'");

                while($dados = $vendas->fetch_array()){
                  $interpretacoes = $dados['Interpretações'];
                  $servicos = $dados['Serviços'];
                  $instrumentos = $dados['Instrumentos'];
                  $total = $dados['Total'];
              ?>
              ['Produtos Vendidos' , <?php echo $interpretacoes ?>, <?php echo $servicos ?>, <?php echo $instrumentos ?>, <?php echo $total ?>],

              <?php } ?>
            ]);
            
            var options = {
              backgroundColor: 'transparent',
              chartArea: {
                backgroundColor: 'transparent',
              },
              title: 'Vendas',
              subtitle: 'Todos os produtos vendidos por você',
            };

            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
          }
        </script>
        <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
        <title>StringMusic</title>
    </head>

    <body>

    <header>
    <a href="index.php" class="logo"><img src="logo/padrão.png" class="nav-logo" alt="Logo"></a>

    <input type="checkbox" id="menu-bar">
    <label for="menu-bar" class="fas fa-bars"></label>

    <nav class="navbar">
        <a href="index.php">Início</a>
        <select name="dropdown" id="dropdown" onchange="javascript: abreJanela(this.value)">
            <option value="loja.php">Loja</option>
            <option value="instrumentos.php">Instrumentos</option>
            <option value="interpretacoes.php">Partituras</option>
            <option value="servico.php">Serviços</option>
        </select>
        <?php
            if(isset($_SESSION['usuario'])){
              echo
              '<div class="action">
                  <div class="profile" onclick="menuAlterna();">
                      <img src="'; echo $imagemusuario_conta; echo'">
                  </div>
                  <div class="menu">
                    <h3>'; echo $nomeusuario_conta; echo '<br><span>'; echo $especialidadeusuario_conta; echo '</span></h3>
                      <ul class="un">
                        <li class="info"><i class="bx bx-user-circle"></i><a href="painel.php">Meu Perfil</a></li>
                        <li class="info"><i class="bx bx-cart"></i><a href="mercadopag/view/mercadopag.php">Carrinho</a></li>
                        <li class="info"><i class="bx bx-envelope"></i><a href="meusprodutos.php">Meus Produtos</a></li>
                        <li class="sair"><i class="bx bx-log-out"></i><a href="logout.php">Sair</a></li>
                        <li class="info_button"><input type="checkbox" name="switch-theme" id="switch">
                        <label for="switch" class="toggle">Toggle</label>
                        <script src="js/script_dark.js"></script></li>
                      </ul>
                  </div>
                </div>';
            }
            else{
              echo '<a href="login.php">Login</a>';
            }
            ?>
    </nav>

</header>

        <section class="section-perfil-usuario">
            <div class="perfil-usuario-fundo" style="background<?php echo $imgfundo ?>">
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
                        <h3>Dashboard</h3>
                        <br>
                        <div id="columnchart_material" style="width: 650px; height: 500px"></div>
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
            <h2 style="color: var(--color-headings)">Partituras</h2>
            <br>
            <main class="grid">

            <?php

                $meusprods = "SELECT s.cd_interpretacao, s.nm_interpretacao, s.ds_interpretacao, s.vl_interpretacao, i.path FROM tb_interpretacao AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$preview_user'";
                $query = $mysqli->query($meusprods);

                while($dados = $query->fetch_array()){
                    echo 
                    '<article>
                        <img src="'; echo $dados['path']; echo '" alt="" style="width: 130px; height: 175px;">
                        <div class="text">
                            <h3>'; echo $dados['nm_interpretacao']; echo '</h3>
                            <p>'; echo $dados['ds_interpretacao']; echo '</p>
                            <p>R$'; echo $dados['vl_interpretacao']; echo '</p>
                            <form method="get" action="produto.php">
                              <input type="text" name="p" style="display: none;" value="'; echo $dados['nm_interpretacao']; echo '">
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
            <br>
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
                                  <input type="text" name="s" style="display: none;" value="'; echo $dados['nm_servico']; echo '">
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

    <script>
            function menuAlterna(){
              const trocaMenu = document.querySelector('.menu');
              trocaMenu.classList.toggle('active');
            }
  </script>
  </body>
</html>