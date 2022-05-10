<?php

include ("conexao.php");
include ("verifica_login.php");

if(isset($_SESSION['usuario'])){
  $session = $_SESSION['usuario'];

  foreach($mysqli->query("SELECT u.cd_usuario AS codigo, u.nm_usuario AS nome, u.sg_especialidade AS especialidade, i.path AS path FROM tb_usuario AS u JOIN tb_imagem AS i ON i.cd_imagem = u.cd_imagem WHERE cd_usuario = '$session'") as $usuarios){
    $nomeusuario = $usuarios['nome'];
    $imagemusuario = $usuarios['path'];
    $codigousuario = $usuarios['codigo'];
    if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
    elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
    elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
  }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>StringMusic</title>
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="js/swal.js"></script>
    <script src="js/clipboard.min.js"></script>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
</head>
<body onload="openTab(event, 'default')">
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
                  <div class="profile" onclick="menuAlterna()">
                      <img src="'; echo $imagemusuario; echo'">
                  </div>
                  <div class="menu">
                    <h3>'; echo $nomeusuario; echo '<br><span>'; echo $especialidadeusuario; echo '</span></h3>
                      <ul class="un">
                        <li class="info"><i class="bx bx-user-circle"></i><a href="painel.php">Meu Perfil</a></li>
                        <li class="info"><i class="bx bx-cart"></i><a href="mercadopag/view/mercadopag.php">Carrinho</a></li>
                        <li class="info"><i class="bx bx-envelope"></i><a href="meusprodutos.php">Meus Produtos</a></li>
                        <li class="info"><i class="bx bx-notepad"></i><a href="minhascompras.php">Minhas Compras</a></li>
                        <li class="info"><i class="bx bx-package"></i><a href="vendidos.php">Minhas Vendas</a></li>
                        <li class="sair"><i class="bx bx-log-out"></i><a href="logout.php">Sair</a></li>
                        <li class="info_button"><input type="checkbox" name="switch-theme" id="switch">
                        <label for="switch" class="toggle">Toggle</label>
                        <script src="js/script_dark.js"></script></li>
                      </ul>
                  </div>
                </div>';
            }
            ?>
    </nav>
</header>

<!-- SweetAlert -->
<?php

if(isset($_SESSION['atualizado'])):
    
    echo '
    <script>
        window.onload = function(){
            Swal.fire({
                icon: "success",
                title: "Data de envio inserida com sucesso!"
            })
        }
    </script>';

    unset($_SESSION['atualizado']);

endif;

?>

    
<div class="tab">
    <button class="tablinks" onclick="openTab(event, 'Part')">Partituras</button>
    <button class="tablinks" onclick="openTab(event, 'Inst')">Instrumentos</button>
    <button class="tablinks" onclick="openTab(event, 'Serv')">Serviços</button>
</div>

<!-- Tabs -->
<script>
    function openTab(evt, jobName) {
      var i, tabcontent, tablinks;
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }
      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
      }
      document.getElementById(jobName).style.display = "block";
      evt.currentTarget.className += " active";
    }
</script>

<div id="default" class="tabcontent">
    <h3 class="heading">Selecione uma <span>aba</span></h3>
</div> 

<div class="container">
    <div id="Part" class="tabcontent">
        <table class="sell-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Data de Compra</th>
                    <th>Visualizar</th>
                </tr>
            </thead>
            <tbody>
            <?php
            
            $confere_int = $mysqli->query("SELECT count(i.cd_interpretacao) from tb_interpretacao as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_interpretacao = c.cd_interpretacao join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session'");
            $result = $confere_int->fetch_assoc();

            if($result != 0){
                $sql = "SELECT i.cd_interpretacao, i.nm_interpretacao, co.dt_compra, co.dt_entrega, co.cd_carrinho from tb_interpretacao as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_interpretacao = c.cd_interpretacao join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session' order by co.dt_compra desc";
                $query = $mysqli->query($sql);

                while($dados = $query->fetch_array()){
                    echo '
                    <tr>
                        <td>'. $dados['nm_interpretacao'] . '</td>
                        <td>'. $dados['dt_compra'] .'</td>';
                        if($dados['dt_entrega'] == NULL){
                            echo '<td><a class="btnpart" href="#popup'; echo $dados['cd_interpretacao'] . $dados['dt_compra']; echo '"><i class="bx bx-search-alt-2"></i></a></td></tr>';
                        } 
                        else echo '<td>Data já inserida!</td></tr>';
                
                    echo '<div id="popup'; echo $dados['cd_interpretacao'] . $dados['dt_compra']; echo '" class="overlay">
                        <div class="popup" style="background-color: var(--bg-panel)">
                            <h1>Indique a data de entrega!</h1>
                            <br>
                            <a class="close" href="#">&times;</a>
                            <div class="content">
                                <form action="enviar_data.php" method="post">
                                    <input type="date" name="data_envio_int" class="field">
                                    <input type="text" name="codigo_int" style="display: none" value="'; echo $dados['cd_carrinho'] .'">
                                    <input type="text" name="data_compra_int" style="display: none" value="'; echo $dados['dt_compra'] .'">
                                    <input type="submit" class="btnpart" value="Enviar">
                                </form>
                            </div>
                        </div>
                    </div>';
                }
            }
            else echo '<h3 class="heading">Você não <span>possui compras de interpretações</span></h3>';

            ?>
            </tbody>
        </table>
    </div>

    <div id="Inst" class="tabcontent">
        <table class="sell-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Data de Compra</th>
                    <th>Visualizar</th>
                </tr>
            </thead>
            <tbody>
            <?php
            
            $confere_int = $mysqli->query("SELECT count(i.cd_instrumento) from tb_instrumento as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_instrumento = c.cd_instrumento join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session'");
            $result = $confere_int->fetch_assoc();

            if($result != 0){
                $sql = "SELECT i.cd_instrumento, i.nm_instrumento, co.dt_compra, co.dt_entrega, co.cd_carrinho from tb_instrumento as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_instrumento = c.cd_instrumento join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session' order by co.dt_compra desc";
                $query = $mysqli->query($sql);

                while($dados = $query->fetch_array()){
                    echo '
                    <tr>
                        <td>'. $dados['nm_instrumento'] . '</td>
                        <td>'. $dados['dt_compra'] .'</td>';
                        if($dados['dt_entrega'] == NULL){
                            echo '<td><a class="btnpart" href="#popup'; echo $dados['cd_instrumento'] . $dados['dt_compra']; echo '"><i class="bx bx-search-alt-2"></i></a></td></tr>';
                        } 
                        else echo '<td>Data já inserida!</td></tr>';
                
                    echo '<div id="popup'; echo $dados['cd_instrumento'] . $dados['dt_compra']; echo '" class="overlay">
                        <div class="popup" style="background-color: var(--bg-panel)">
                            <h1>Indique a data de entrega!</h1>
                            <br>
                            <a class="close" href="#">&times;</a>
                            <div class="content">
                                <form action="enviar_data.php" method="post">
                                    <input type="date" name="data_envio_ins" class="field">
                                    <input type="text" name="codigo_ins" style="display: none" value="'; echo $dados['cd_carrinho'] .'">
                                    <input type="text" name="data_compra_ins" style="display: none" value="'; echo $dados['dt_compra'] .'">
                                    <input type="submit" class="btnpart" value="Enviar">
                                </form>
                            </div>
                        </div>
                    </div>';
                }
            }
            else echo '<h3 class="heading">Você não <span>possui compras de instrumentos</span></h3>';

            ?>
            </tbody>
        </table>
    </div>

    <div id="Serv" class="tabcontent">
        <table class="sell-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Data de Compra</th>
                    <th>Visualizar</th>
                </tr>
            </thead>
            <tbody>
            <?php
            
            $confere_int = $mysqli->query("SELECT count(i.cd_servico) from tb_servico as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_servico = c.cd_servico join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session'");
            $result = $confere_int->fetch_assoc();

            if($result != 0){
                $sql = "SELECT i.cd_servico, i.nm_servico, co.dt_compra, co.dt_entrega, co.cd_carrinho from tb_servico as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_servico = c.cd_servico join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session' order by co.dt_compra desc";
                $query = $mysqli->query($sql);

                while($dados = $query->fetch_array()){
                    echo '
                    <tr>
                        <td>'. $dados['nm_servico'] . '</td>
                        <td>'. $dados['dt_compra'] .'</td>';
                        if($dados['dt_entrega'] == NULL){
                            echo '<td><a class="btnpart" href="#popup'; echo $dados['cd_servico'] . $dados['dt_compra']; echo '"><i class="bx bx-search-alt-2"></i></a></td></tr>';
                        } 
                        else echo '<td>Data já inserida!</td></tr>';
                
                    echo '<div id="popup'; echo $dados['cd_servico'] . $dados['dt_compra']; echo '" class="overlay">
                        <div class="popup" style="background-color: var(--bg-panel)">
                            <h1>Indique a data de entrega!</h1>
                            <br>
                            <a class="close" href="#">&times;</a>
                            <div class="content">
                                <form action="enviar_data.php" method="post">
                                    <input type="date" name="data_envio_ser" class="field">
                                    <input type="text" name="codigo_ser" style="display: none" value="'; echo $dados['cd_carrinho'] .'">
                                    <input type="text" name="data_compra_ser" style="display: none" value="'; echo $dados['dt_compra'] .'">
                                    <input type="submit" class="btnpart" value="Enviar">
                                </form>
                            </div>
                        </div>
                    </div>';
                }
            }
            else echo '<h3 class="heading">Você não <span>possui compras de interpretações</span></h3>';

            ?>
            </tbody>
        </table>
    </div>
    

</div> <!-- container -->

<!--<footer>
    <div class="row">
        <div class="col">
            <img src="img/S_disco4.png" class="footer-logo">
            <p>Somos um grupo composto por quatro integrantes, na realização do trabalho de conclusão de curso na formação de Desenvolvimento de Sistemas</p>
        </div>
        <div class="col">
            <h3>String;Music</h3>
            <p>ETEC Dra Ruth Cardoso</p>
            <p>Pr. Cel. Lopes, 387</p>
            <p>São Vicente - SP</p>
            <p>CEP: 11310-020</p>
            <p class="email-id">email@email.com</p>
            <h4>+55 5555-5555</h4>
        </div>
        <div class="col">
            <div class="media">
            <h3>Redes Sociais</h3>
            <p style="margin-bottom: 30px;">Nos siga também em nossas redes sociais para ficar por dentro de todas as atualizações de nosso site!</p>
                <div class="social-media">
                <a href="#" class="social-icon">
                    <i class='bx bxl-facebook-square' ></i>
                </a>
                <a href="#" class="social-icon">
                    <i class='bx bxl-instagram' ></i>
                </a>
                <a href="#" class="social-icon">
                    <i class='bx bxl-youtube' ></i>
                </a>
                </div>
            </div>
        </div>

</footer>-->

<script>
    function menuAlterna(){
      const trocaMenu = document.querySelector('.menu');
      trocaMenu.classList.toggle('active');
    }
  </script>  

<style>

    .box{
    width: 40%;
    margin: 0 auto;
    background: rgba(255,255,255,0.2);
    padding: 35px;
    border: 2px solid #fff;
    border-radius: 20px/50px;
    background-clip: padding-box;
    text-align: center;
    }

    .button{
    font-size: 1em;
    padding: 10px;
    color: black;
    border: 2px solid black;
    border-radius: 20px/50px;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease-out;
    }
    .button:hover{
    background: #3770db;
    }

    .overlay{
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.7);
    transition: opacity 500ms;
    visibility: hidden;
    opacity: 0;
    }
    .overlay:target{
    visibility: visible;
    opacity: 1;
    }

    .popup{
    margin: 70px auto;
    padding: 20px;
    background: #fff;
    border-radius: 5px;
    width: 30%;
    position: relative;
    transition: all 5s ease-in-out;
    }

    .popup h2{
    margin-top: 0;
    color: #333;
    font-family: Tahoma, Arial, sans-serif;
    }
    .popup .close{
    position: absolute;
    top: 20px;
    right: 30px;
    transition: all 200ms;
    font-size: 30px;
    font-weight: bold;
    text-decoration: none;
    color: #333;
    }
    .popup .close:hover{
    color: #3770db;
    }
    .popup .content{
    max-height: 30%;
    overflow: auto;
    }

    @media screen and (max-width: 700px){
    .box{
        width: 70%;
    }
    .popup{
        width: 70%;
    }
    }

    #comments{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 100%;
        }

        .comments-heading{
            letter-spacing: 1px;
            margin: 30px 0px;
            padding: 10px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .comments-heading h1{
            font-size: 2.2rem;
            font-weight: 500;
            background-color: #3770db;
            color: #fff;
            padding: 10px 20px;
        }

        .comments-heading span{
            font-size: 1.3rem;
            color: #252525;
            margin-bottom: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .comments-box-container{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            width: 100%;
        }

        .comments-box{
            width: 500px;
            box-shadow: 2px 2px 30px rgba(0,0,0,0.5);
            background-color: #fff;
            padding: 20px;
            margin: 15px;
            cursor: pointer;
        }

        .profile-img{
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 10px;
        }

        .profile-img img{
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-fit: center;
        }

        .profile{
            display: flex;
            align-items: center;
        }

        .name-user{
            display: flex;
            flex-direction: column;
        }

        .name-user strong{
            color: #3d3d3d;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }

        .name-user span{
            color: #979797;
            font-size: 0.8rem;
        }

        .reviews{
            color: #f9d71c;
        }

    .box-top{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .user-comment p{
        font-size: 0.9rem;
        color: #4b4b4b;
    }

</style>

</body>
</html>