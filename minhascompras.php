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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StringMusic</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <!--SWAL-->
    <script src="js/swal.js"></script>
    <script src="js/clipboard.min.js"></script>
</head>
<body>
    <header>
        <a href="index.php" class="logo"><img src="logo/padrão.png" id="logo" class="nav-logo" alt="Logo"></a>

        <input type="checkbox" id="menu-bar">
        <label for="menu-bar" class="fas fa-bars"></label>

        <nav class="navbar">
            <a href="index.php">Início</a>
            <a href="loja.php">Loja</a>
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
    <div class="tab">
      <button class="tablinks" onclick="openTab(event, 'Part')">Partituras</button>
      <button class="tablinks" onclick="openTab(event, 'Inst')">Instrumentos</button>
      <button class="tablinks" onclick="openTab(event, 'Serv')">Serviços</button>
    </div>
    
    <div id="Part" class="tabcontent">
        
        <section class="product" id="product">
        <?php
            $busca_prod = "SELECT count(s.cd_interpretacao) as codigo from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_interpretacao as s on s.cd_interpretacao = c.cd_interpretacao join tb_imagem as i on i.cd_imagem = s.cd_imagem where co.cd_usuario = '$codigousuario'";
            $busca_prod = $mysqli->query($busca_prod);
            $busca_prod = $busca_prod->fetch_assoc();
            $confirma_prod = $busca_prod['codigo'];
            if($confirma_prod == 0){
                echo '<h3 class="heading">Você não <span>possui compras de interpretações</span></h3>';
            }
            else{?>
            <div class="box-container">
                <?php
                    foreach($mysqli->query("SELECT s.sg_tipo, s.cd_interpretacao, co.dt_entrega, s.nm_interpretacao, s.vl_interpretacao, i.path, co.nm_token, a.nm_path, (SELECT format(avg(f.qt_feedback), 1) from tb_feedback as f where f.cd_interpretacao = s.cd_interpretacao) as feedback from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_interpretacao as s on s.cd_interpretacao = c.cd_interpretacao join tb_imagem as i on i.cd_imagem = s.cd_imagem left join tb_arquivo as a on a.cd_arquivo = s.cd_arquivo where co.cd_usuario = '$codigousuario' order by co.dt_compra desc") as $dados){
                        echo '<div class="box">
                        <div class="icons">
                            <a href="#" class="fas fa-share"></a>
                            <button class="btn'; echo $dados['nm_interpretacao']; echo'" data-clipboard-text="https://localhost/stringmusic/produto.php?p='; echo $dados['nm_interpretacao']; echo '"><a class="fas fa-copy"></a></button>
                        </div>';?>
                        <script>
                            var button = document.getElementsByClassName("btn<?php echo $dados['nm_interpretacao']?>");
                            new ClipboardJS(button);
                        </script>
                        <?php echo'
                        <img src="'; echo $dados['path']; echo '" alt="">
                        <h3>'; 
                        if(strlen($dados['nm_interpretacao']) > 14)
                            echo str_replace(substr($dados['nm_interpretacao'], 11), '...', $dados['nm_interpretacao']);
                        else
                            echo $dados['nm_interpretacao'];
                        echo '</h3>';
                        if($dados['feedback'] == NULL)
                            echo '
                            <div class="stars">
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) < 1 && floatval($dados['feedback']) != NULL)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) == 1)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) > 1 && floatval($dados['feedback']) < 2)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) == 2)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) > 2 && floatval($dados['feedback']) < 3)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) == 3)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) > 3 && floatval($dados['feedback']) < 4)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) == 4)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) > 4 && floatval($dados['feedback']) < 5)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>';
                        elseif(floatval($dados['feedback']) == 5)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i> 
                            </div>';

                        echo '<div class="price"> R$'; echo $dados['vl_interpretacao'];  echo '</div>
                        <button class="btnpart" onclick="viewprod'. $dados['cd_interpretacao'] .'()">Visualizar</button>
                    </div>';
                    ?>
                    <script>
                        function viewprod<?= $dados['cd_interpretacao']?>(){
                            Swal.fire({
                                imageUrl: '<?= $dados['path'] ?>',
                                imageHeight: 300,
                                title: '<?= $dados['nm_interpretacao'] ?>',
                                html: 'Ticket de compra: <?= $dados['nm_token'] ?> <br> Data de entrega: <?php if($dados['dt_entrega'] == null) echo 'O vendedor tem até 15 dias postar seu produto'; else echo $dados['dt_entrega']; ?> <br> Produto <b><?php if($dados['sg_tipo'] == "f") echo 'Físico'; else echo 'Virtual'; ?></b> <br> <?php if($dados['sg_tipo'] == "v") echo '<a href="'. $dados['nm_path'] . '" download="' . $dados['nm_interpretacao'].'">Baixar!</a>'; ?>'
                            })
                        }
                    </script>
                    <?php }?>
            </div>
            
            <?php }?>
        </section>

    </div>
    
    <div id="Inst" class="tabcontent">
      
        <section class="product" id="product">
        <?php
            $busca_prod = "SELECT count(s.cd_instrumento) as codigo from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_instrumento as s on s.cd_instrumento = c.cd_instrumento join tb_imagem as i on i.cd_imagem = s.cd_imagem where co.cd_usuario = '$codigousuario'";
            $busca_prod = $mysqli->query($busca_prod);
            $busca_prod = $busca_prod->fetch_assoc();
            $confirma_prod = $busca_prod['codigo'];
            if($confirma_prod == 0){
                echo '<h3 class="heading">Você não <span>possui compras de instrumentos</span></h3>';
            }
            else{?>
            <div class="box-container">
                <?php
                foreach($mysqli->query("SELECT s.cd_instrumento, s.nm_instrumento, s.vl_instrumento, i.path, co.nm_token, co.dt_entrega, (SELECT format(avg(f.qt_feedback), 1) from tb_feedback as f where f.cd_instrumento = s.cd_instrumento) as feedback from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_instrumento as s on s.cd_instrumento = c.cd_instrumento join tb_imagem as i on i.cd_imagem = s.cd_imagem where co.cd_usuario = '$codigousuario' order by co.dt_compra asc") as $dados){
                        echo '<div class="box">
                        <div class="icons">
                            <a href="#" class="fas fa-share"></a>
                            <button class="btn'; echo $dados['nm_instrumento']; echo'" data-clipboard-text="https://localhost/stringmusic/produto.php?p='; echo $dados['nm_instrumento']; echo '"><a class="fas fa-copy"></a></button>
                        </div>';?>
                        <script>
                            var button = document.getElementsByClassName("btn<?php echo $dados['nm_instrumento']?>");
                            new ClipboardJS(button);
                        </script>
                        <?php echo'
                        <img src="'; echo $dados['path']; echo '" alt="">
                        <h3>'; 
                        if(strlen($dados['nm_instrumento']) > 14)
                            echo str_replace(substr($dados['nm_instrumento'], 11), '...', $dados['nm_instrumento']);
                        else
                            echo $dados['nm_instrumento'];
                        echo '</h3>';
                        if($dados['feedback'] == NULL)
                            echo '
                            <div class="stars">
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) < 1 && floatval($dados['feedback']) != NULL)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) == 1)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) > 1 && floatval($dados['feedback']) < 2)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) == 2)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) > 2 && floatval($dados['feedback']) < 3)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) == 3)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) > 3 && floatval($dados['feedback']) < 4)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) == 4)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i> 
                            </div>';
                        elseif(floatval($dados['feedback']) > 4 && floatval($dados['feedback']) < 5)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>';
                        elseif(floatval($dados['feedback']) == 5)
                            echo '
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i> 
                            </div>';
                        echo '<div class="price"> R$'; echo $dados['vl_instrumento'];  echo '</div>
                        <button class="btnpart" onclick="viewinst'. $dados['cd_instrumento'] .'()">Visualizar</button>
                        </div>';
                        ?>
                        <script>
                            function viewinst<?php echo $dados['cd_instrumento']?>(){
                                Swal.fire({
                                    imageUrl: '<?php echo $dados['path'] ?>',
                                    imageHeight: 300,
                                    title: '<?php echo $dados['nm_instrumento'] ?>',
                                    html: 'Ticket de compra: <?php echo $dados['nm_token'] ?> <br> Data de entrega: <?php if($dados['dt_entrega'] == null) echo 'O vendedor tem até 15 dias postar seu produto'; else echo $dados['dt_entrega']; ?>'
                                })
                            }
                        </script>
                        <?php }?>
                </div>
                
                <?php }?>
        </section>

    </div>
    
    <div id="Serv" class="tabcontent">
      
        <section class="product" id="product">
            <?php
                $busca_prod = "SELECT count(s.cd_servico) as codigo from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_servico as s on s.cd_servico = c.cd_servico join tb_imagem as i on i.cd_imagem = s.cd_imagem where co.cd_usuario = '$codigousuario'";
                $busca_prod = $mysqli->query($busca_prod);
                $busca_prod = $busca_prod->fetch_assoc();
                $confirma_prod = $busca_prod['codigo'];
                if($confirma_prod == 0){
                    echo '<h3 class="heading">Você não <span>possui compras de serviços</span></h3>';
                }
                else{?>
                <div class="box-container">
                    <?php
                        foreach($mysqli->query("SELECT s.cd_servico, s.nm_servico, s.vl_servico, i.path, co.nm_token, co.dt_entrega, (SELECT format(avg(f.qt_feedback), 1) from tb_feedback as f where f.cd_servico = s.cd_servico) as feedback from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_servico as s on s.cd_servico = c.cd_servico join tb_imagem as i on i.cd_imagem = s.cd_imagem where co.cd_usuario = '$codigousuario'") as $dados){
                            echo '<div class="box">
                            <div class="icons">
                                <a href="#" class="fas fa-share"></a>
                                <button class="btn'; echo $dados['nm_servico']; echo'" data-clipboard-text="https://localhost/stringmusic/produto.php?p='; echo $dados['nm_servico']; echo '"><a class="fas fa-copy"></a></button>
                            </div>';?>
                            <script>
                                var button = document.getElementsByClassName("btn<?php echo $dados['nm_servico']?>");
                                new ClipboardJS(button);
                            </script>
                            <?php echo'
                            <img src="'; echo $dados['path']; echo '" alt="">
                            <h3>'; 
                            if(strlen($dados['nm_servico']) > 14)
                                echo str_replace(substr($dados['nm_servico'], 11), '...', $dados['nm_servico']);
                            else
                                echo $dados['nm_servico'];
                            echo '</h3>';
                            if($dados['feedback'] == NULL)
                                echo '
                                <div class="stars">
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i> 
                                </div>';
                            elseif(floatval($dados['feedback']) < 1 && floatval($dados['feedback']) != NULL)
                                echo '
                                <div class="stars">
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i> 
                                </div>';
                            elseif(floatval($dados['feedback']) == 1)
                                echo '
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i> 
                                </div>';
                            elseif(floatval($dados['feedback']) > 1 && floatval($dados['feedback']) < 2)
                                echo '
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i> 
                                </div>';
                            elseif(floatval($dados['feedback']) == 2)
                                echo '
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i> 
                                </div>';
                            elseif(floatval($dados['feedback']) > 2 && floatval($dados['feedback']) < 3)
                                echo '
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i> 
                                </div>';
                            elseif(floatval($dados['feedback']) == 3)
                                echo '
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i> 
                                </div>';
                            elseif(floatval($dados['feedback']) > 3 && floatval($dados['feedback']) < 4)
                                echo '
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i> 
                                </div>';
                            elseif(floatval($dados['feedback']) == 4)
                                echo '
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i> 
                                </div>';
                            elseif(floatval($dados['feedback']) > 4 && floatval($dados['feedback']) < 5)
                                echo '
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>';
                            elseif(floatval($dados['feedback']) == 5)
                                echo '
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i> 
                                </div>';
                            echo '<div class="price"> R$'; echo $dados['vl_servico'];  echo '</div>
                            <button class="btnpart" onclick="viewserv'. $dados['cd_servico'] .'()">Visualizar</button>
                        </div>';
                        ?>
                        <script>
                            function viewserv<?php echo $dados['cd_servico']?>(){
                                Swal.fire({
                                    imageUrl: '<?php echo $dados['path'] ?>',
                                    imageHeight: 300,
                                    title: '<?php echo $dados['nm_servico'] ?>',
                                    html: 'Ticket de compra: <?php echo $dados['nm_token'] ?> <br> Data de entrega: <?php if($dados['dt_entrega'] == null) echo 'O vendedor tem até 15 dias postar seu produto'; else echo $dados['dt_entrega']; ?>'
                                })
                            }
                        </script>
                        <?php }?>
                </div>
                
                <?php }?>
        </section>

    </div>

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

</body>
</html>