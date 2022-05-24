<?php

include("foto.php");

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>String;Music</title>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <script src="js/script.js"></script>
    <script src="js/swal.js"></script>
    <!-- FONT AWESOME ICONS  -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styleloja.css">
    <script src="js/clipboard.min.js"></script>
</head>
<body>
<!-- HEADER -->
<header>
    <a href="index.php" class="logo"><img src="logo/padrão.png" class="nav-logo" alt="Logo"></a>

    <input type="checkbox" id="menu-bar">
    <label for="menu-bar" class="fas fa-bars"></label>

    <nav class="navbar">
        <a href="index.php">Início</a>
        <select name="dropdown" id="dropdown" onchange="javascript: abreJanela(this.value)">
            <option value="loja.php" selected>Loja</option>
            <option value="instrumentos.php">Instrumentos</option>
            <option value="interpretacoes.php">Partituras</option>
            <option value="servico.php">Serviços</option>
        </select>
        <?php
            if(isset($_SESSION['usuario'])){
                foreach($mysqli->query("SELECT count(i.cd_interpretacao) + (SELECT count(i.cd_instrumento) from tb_instrumento as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_instrumento = c.cd_instrumento join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session' and co.dt_entrega is NULL) + (SELECT count(i.cd_servico) from tb_servico as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_servico = c.cd_servico join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session' and co.dt_entrega is NULL) as 'vendas' from tb_interpretacao as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_interpretacao = c.cd_interpretacao join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session' and co.dt_entrega is NULL") as $quantidade){
                    $vendas = $quantidade['vendas'];
                }
              echo
              '<div class="action">
                  <div class="profile" onclick="menuAlterna();">
                      <img src="'; echo $imagemusuario; echo'">
                  </div>
                  <div class="menu" style="top: 90px !important; right: 8.5% !important">
                    <h3>'; echo $nomeusuario; echo '<br><span>'; echo $especialidadeusuario; echo '</span></h3>
                      <ul class="un">
                        <li class="info"><i class="bx bx-user-circle"></i><a href="painel.php">Meu Perfil</a></li>
                        <li class="info"><i class="bx bx-cart"></i><a href="mercadopag/view/mercadopag.php">Carrinho</a></li>
                        <li class="info"><i class="bx bx-envelope"></i><a href="meusprodutos.php">Meus Produtos</a></li>
                        <li class="info"><i class="bx bx-notepad"></i><a href="minhascompras.php">Minhas Compras</a></li>
                        <li class="info"><i class="bx bx-package"></i><a href="vendidos.php">Minhas Vendas</a><span class="dotAlert">'; echo $vendas; echo '</span></li>
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

<!-- HEADER FIM -->

<!-- BANNER -->

<br><br><br><br><br><br>

<section class="banner-container">
    <div class="banner">
        <a href="">
            <img src="imgs/banner_dois.png" alt="">
        </a>
    </div>

    <div class="banner">
        <a href="">
            <img src="imgs/banner_um.png" alt="">
        </a>
    </div>
</section>

<!-- BANNER FIM -->

<!-- CATEGORIA -->
<section class="category" id="category">

    <h1 class="heading">Comprar por <span>categoria</span></h1>

    <div class="box-container">

        <div class="box">
            <h3>Instrumentos</h3>
            <img src="imgs/instrumentos.png" alt="">
            <a href="instrumentos.php" class="btn">Acessar</a>
        </div>
        <div class="box">
            <h3>Partituras</h3>
            <img src="imgs/partituras.png" alt="">
            <a href="interpretacoes.php" class="btn">Acessar</a>
        </div>
        <div class="box">
            <h3>Serviços</h3>
            <img src="imgs/serviços.png" alt="">
            <a href="servico.php" class="btn">Acessar</a>
        </div>

    </div>

</section>

<!-- CATEGORIA FIM -->

<!-- PRODUTOS  -->

<section class="product" id="product">

    <h3 class="heading">Produtos mais <span>comprados</span></h3>

    <div class="box-container">
    
    <?php
        $sql = $mysqli->query("SELECT c.cd_carrinho
        from tb_carrinho as c
            join tb_compra as co
                on c.cd_carrinho = co.cd_carrinho
                    where c.nm_inativo = 1
                          group by c.cd_interpretacao, c.cd_instrumento, c.cd_servico
                            order by sum(c.qt_carrinho) desc
                                limit 4");

        while($dados = $sql->fetch_array()){
            $cod_car = $dados['cd_carrinho'];
            foreach($mysqli->query("SELECT s.cd_interpretacao, s.nm_interpretacao, s.vl_interpretacao, i.path, (SELECT format(avg(f.qt_feedback), 1) from tb_feedback as f where f.cd_interpretacao = s.cd_interpretacao) as feedback FROM tb_carrinho AS c JOIN tb_interpretacao AS s on s.cd_interpretacao = c.cd_interpretacao JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem WHERE c.cd_carrinho = '$cod_car' AND s.nm_inativo = 0") as $interp){
                echo '<div class="box">
                    <div class="icons">
                        <a class="fas fa-share"></a>
                        <button class="btn'; echo $interp['nm_interpretacao']; echo'" data-clipboard-text="https://localhost/stringmusic/produto.php?p='; echo $interp['nm_interpretacao']; echo '"><a class="fas fa-copy"></a></button>
                        </div>';
                        ?>
                        <script>
                            var button = document.getElementsByClassName("btn<?php echo $interp['nm_interpretacao']?>");
                            new ClipboardJS(button);
                        </script>
                        <?php echo '
                    <img src="'; echo $interp['path']; echo '" alt="">
                    <h3>'; 
                    if(strlen($interp['nm_interpretacao']) > 14)
                        echo str_replace(substr($interp['nm_interpretacao'], 11), '...', $interp['nm_interpretacao']);
                    else
                        echo $interp['nm_interpretacao'];
                    echo '</h3>';
                    if($interp['feedback'] == NULL)
                        echo '
                        <div class="stars">
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) < 1 && floatval($interp['feedback']) != NULL)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) == 1)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) > 1 && floatval($interp['feedback']) < 2)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) == 2)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) > 2 && floatval($interp['feedback']) < 3)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) == 3)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) > 3 && floatval($interp['feedback']) < 4)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) == 4)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) > 4 && floatval($interp['feedback']) < 5)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>';
                    elseif(floatval($interp['feedback']) == 5)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i> 
                        </div>';
                    echo '<div class="price"> R$'; echo $interp['vl_interpretacao'];  echo '</div>
                    <form method="get" action="produto.php" style="display: inline-block;">
                        <input type="text" name="p" style="display: none;" value="'; echo $interp['nm_interpretacao']; echo '">
                        <input type="submit" class="btn" value="Comprar">
                    </form>
                </div>';
            }
            foreach($mysqli->query("SELECT s.cd_servico, s.nm_servico, s.vl_servico, i.path, (SELECT format(avg(f.qt_feedback), 1) from tb_feedback as f where f.cd_servico = s.cd_servico) as feedback FROM tb_carrinho AS c JOIN tb_servico AS s on s.cd_servico = c.cd_servico JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem WHERE c.cd_carrinho = '$cod_car' AND s.nm_inativo = 0") as $interp){
                echo '<div class="box">
                        <div class="icons">
                        <a class="fas fa-share"></a>
                        <button class="btn'; echo $interp['nm_servico']; echo'" data-clipboard-text="https://localhost/stringmusic/prodserv.php?s='; echo $interp['nm_servico']; echo '"><a class="fas fa-copy"></a></button>
                        </div>';
                        ?>
                        <script>
                            var button = document.getElementsByClassName("btn<?php echo $interp['nm_servico']?>");
                            new ClipboardJS(button);
                        </script>
                        <?php echo '
                    <img src="'; echo $interp['path']; echo '" alt="">
                    <h3>'; 
                    if(strlen($interp['nm_servico']) > 14)
                        echo str_replace(substr($interp['nm_servico'], 11), '...', $interp['nm_servico']);
                    else
                        echo $interp['nm_servico'];
                    echo '</h3>';
                    if($interp['feedback'] == NULL)
                        echo '
                        <div class="stars">
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) < 1 && floatval($interp['feedback']) != NULL)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) == 1)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) > 1 && floatval($interp['feedback']) < 2)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) == 2)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) > 2 && floatval($interp['feedback']) < 3)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) == 3)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) > 3 && floatval($interp['feedback']) < 4)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) == 4)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) > 4 && floatval($interp['feedback']) < 5)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>';
                    elseif(floatval($interp['feedback']) == 5)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i> 
                        </div>';
                    echo '<div class="price"> R$'; echo $interp['vl_servico'];  echo '</div>
                    <form method="get" action="prodserv.php" style="display: inline-block;">
                        <input type="text" name="s" style="display: none;" value="'; echo $interp['nm_servico']; echo '">
                        <input type="submit" class="btn" value="Comprar">
                    </form>
                </div>';
            }
            foreach($mysqli->query("SELECT s.cd_instrumento, s.nm_instrumento, s.vl_instrumento, i.path, (SELECT format(avg(f.qt_feedback), 1) from tb_feedback as f where f.cd_instrumento = s.cd_instrumento) as feedback FROM tb_carrinho AS c JOIN tb_instrumento AS s on s.cd_instrumento = c.cd_instrumento JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem WHERE c.cd_carrinho = '$cod_car' AND s.nm_inativo = 0") as $interp){
                echo '<div class="box">
                        <div class="icons">
                        <a class="fas fa-share"></a>
                        <button class="btn'; echo $interp['nm_instrumento']; echo'" data-clipboard-text="https://localhost/stringmusic/prodinst.php?i='; echo $interp['nm_instrumento']; echo '"><a class="fas fa-copy"></a></button>
                        </div>';
                        ?>
                        <script>
                            var button = document.getElementsByClassName("btn<?php echo $interp['nm_instrumento']?>");
                            new ClipboardJS(button);
                        </script>
                        <?php echo '
                    <img src="'; echo $interp['path']; echo '" alt="">
                    <h3>'; 
                    if(strlen($interp['nm_instrumento']) > 14)
                        echo str_replace(substr($interp['nm_instrumento'], 11), '...', $interp['nm_instrumento']);
                    else
                        echo $interp['nm_instrumento'];
                    echo '</h3>';
                    if($interp['feedback'] == NULL)
                        echo '
                        <div class="stars">
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) < 1 && floatval($interp['feedback']) != NULL)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) == 1)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) > 1 && floatval($interp['feedback']) < 2)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) == 2)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) > 2 && floatval($interp['feedback']) < 3)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) == 3)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) > 3 && floatval($interp['feedback']) < 4)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) == 4)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i> 
                        </div>';
                    elseif(floatval($interp['feedback']) > 4 && floatval($interp['feedback']) < 5)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>';
                    elseif(floatval($interp['feedback']) == 5)
                        echo '
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i> 
                        </div>';
                    echo '<div class="price"> R$'; echo $interp['vl_instrumento'];  echo '</div>
                    <form method="get" action="prodinst.php" style="display: inline-block;">
                        <input type="text" name="i" style="display: none;" value="'; echo $interp['nm_instrumento']; echo '">
                        <input type="submit" class="btn" value="Comprar">
                    </form>
                </div>';
            }
        }
    ?>

    </div>

</section>

    
</body>
</html>