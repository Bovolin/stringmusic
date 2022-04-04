<?php
  include ("fundo_foto.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/styleusuarios.css" />
        <script src="js/swal.js"></script>
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
                                          on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session') as 'Serviços',
                    (select count(ins.cd_instrumento) 
                        from tb_instrumento as ins
                            join tb_usuario as u
                                on u.cd_usuario = ins.cd_usuario
                                    join tb_carrinho as c
                                        on ins.cd_instrumento = c.cd_instrumento
                                          join tb_compra as co
                                            on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session') as 'Instrumentos',
                    count(i.cd_interpretacao) + (select count(s.cd_servico)
                      from tb_servico as s
                        join tb_usuario as u
                                on u.cd_usuario = s.cd_usuario
                                      join tb_carrinho as c
                                        on s.cd_servico = c.cd_servico
                                          join tb_compra as co
                                          on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session') + (select count(ins.cd_instrumento) 
                          from tb_instrumento as ins
                              join tb_usuario as u
                                  on u.cd_usuario = ins.cd_usuario
                                      join tb_carrinho as c
                                          on ins.cd_instrumento = c.cd_instrumento
                                            join tb_compra as co
                                              on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session') as 'Total'
                      from tb_interpretacao as i
                          join tb_usuario as u
                              on u.cd_usuario = i.cd_usuario
                                  join tb_carrinho as c
                                      on i.cd_interpretacao = c.cd_interpretacao
                                        join tb_compra as co
                                              on c.cd_carrinho = co.cd_carrinho
                                                where u.cd_usuario = '$session'");

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
              backgroundColor: window.getComputedStyle(document.querySelector('.perfil-usuario-avatar')).backgroundColor,
              chartArea: {
                backgroundColor: window.getComputedStyle(document.querySelector('.perfil-usuario-avatar')).backgroundColor,
              },
              chart: {
                title: 'Vendas',
                subtitle: 'Todos os produtos vendidos por você',
              }
            };

            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
          }
        </script>
        <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
        <title>StringMusic</title>
    </head>

    <?php
    if(isset($_SESSION['cred_alterada'])) $onload = "cred_alterada()";

    if(isset($_SESSION['cred_alterada'])):
    ?>
    <script>
      function cred_alterada(){
        Swal.fire({
          icon: 'success',
          text: 'Credenciais alteradas com sucesso!'
        })
      }
    </script>
    <?php
    endif;
    unset($_SESSION['cred_alterada']);
    ?>
    <body onload="<?php echo $onload ?>">
    <header>
    <a href="index.php" class="logo"><img src="logo/padrão.png" class="nav-logo" alt="Logo"></a>

    <input type="checkbox" id="menu-bar">
    <label for="menu-bar" class="fas fa-bars"></label>

    <nav class="navbar">
        <a href="index.php">Início</a>
        <a href="loja.php">Loja</a>
        <?php
            if(isset($_SESSION['usuario'])){
              echo
              '<div class="action">
                  <div class="profile" onclick="menuAlterna();">
                      <img src="'; echo $imgusuario; echo'">
                  </div>
                  <div class="menu">
                    <h3>'; echo $nomeusuario; echo '<br><span>'; echo $especialidadeusuario; echo '</span></h3>
                      <ul class="un">
                        <li class="info"><i class="bx bx-user-circle"></i><a href="painel.php">Meu Perfil</a></li>
                        <li class="info"><i class="bx bx-cart"></i><a href="mercadopag/view/mercadopag.php">Carrinho</a></li>
                        <li class="info"><i class="bx bx-envelope"></i><a href="meusprodutos.php">Meus Produtos</a></li>
                        <li class="sair"><i class="bx bx-log-out"></i><a href="logout.php">Sair</a></li>
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
                <button type="button" class="button-avatar" id="loadFileXml" onclick="document.getElementById('file').click();">
                  <i class="fas fa-camera"></i>
                  <?php
                    if(isset($_SESSION['fotoinserida'])):
                  ?>
                  <script>
                    function fotoenviada(){
                      Swal.fire({
                        icon: 'success',
                        title: 'Foto enviada com sucesso!'
                      })
                    }
                  </script>
                  <?php
                    endif;
                    unset($_SESSION['fotoinserida']);
                  ?>
                  <form action="mudarfoto.php" method="post" id="form" onsubmit="fotoenviada()" enctype="multipart/form-data">
                    <input type="file" style="display:none;" id="file" name="file" accept="image/png, image/jpg, image/jpeg">
                  </form>
                </button> 
              </div>
              <button type="button" class="button-fundo" id="loadFileXml" onclick="document.getElementById('fundo').click();">
                <i class="fas fa-images"></i>Mudar fundo
              </button>
              <form action="mudarfundo.php" method="post" id="form2" enctype="multipart/form-data">
                <input type="file" style="display: none;" id="fundo" name="fundo" accept="image/png, img/jpg, image/jpeg">
              </form>
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
              <a href="editarperfil.php" class="boton-redes instagram fas fa-user-edit" style="background: linear-gradient(45deg, #336BB8, #37B82A);"><i class="icon-facebook"></i></a>
              <a href="" class="boton-redes facebook fab fa-facebook-f"><i class="icon-facebook"></i></a>
              <a href="" class="boton-redes instagram fab fa-instagram"><i class="icon-instagram"></i></a>
              <input type="checkbox" name="switch-theme" id="switch">
              <label for="switch">Toggle</label>
            </div>
          </div>
        </section>

        <script src="js/script_dark.js"></script>

    <script>
          document.getElementById("file").onchange = function() {
            document.getElementById("form").submit();
          };

          document.getElementById("fundo").onchange = function() {
            document.getElementById("form2").submit();
          };
    </script>
        
    <!-- Menu navbar -> script interno -->
    <script>
          function menuAlterna(){
            const trocaMenu = document.querySelector('.menu');
            trocaMenu.classList.toggle('active');
          }

          checkbox.addEventListener("change", ({ target }) => {
            if(target.checked){
              changeColors(darkMode)
              location.reload();
            }
            else{
              changeColors(initialColors)
              location.reload();
            }
            localStorage.setItem('color-mode', target.checked)
          })
    </script>
        
    
    </body>

</html>