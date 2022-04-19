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
<html lang="pt-BR" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>StringMusic</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                echo
                '<div class="action">
                    <div class="profile" onclick="menuAlterna();">
                        <img src="'; echo $imagemusuario; echo'">
                    </div>
                    <div class="menu">
                        <h3>'; echo $nomeusuario; echo '<br><span>'; echo $especialidadeusuario; echo '</span></h3>
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

    <section class="product" id="product">
        <?php
            $busca_compras = "SELECT COUNT(co.cd_compra) as compra from tb_compra as co where co.cd_usuario = '$session'";
            $busca_compras = $mysqli->query($busca_compras);
            $busca_compras = $busca_compras->fetch_assoc();
            $result = $busca_compras['compra'];
            if($result == 0) echo '<h3>Você não <span>produtos comprados</span></h3>';
            else{
        ?>
        <h3 class="heading">Produtos <span>comprados</span></h3>
        <div class="box-container">
            <?php
                $busca_interp = "SELECT i.nm_interpretacao as nome, i.vl_interpretacao as preco, im.path as imagem from tb_compra as co join tb_carrinho as ca on ca.cd_carrinho = co.cd_carrinho join tb_interpretacao as i on i.cd_interpretacao = ca.cd_interpretacao join tb_imagem as im on im.cd_imagem = i.cd_imagem where co.cd_usuario = '$session'";
                $query_interp = $mysqli->query($busca_interp);

                while($dados_interp = $query_interp->fetch_array()){
                    echo 
                    '<div class="box">
                        <div class="icons">
                        <a href="#" class="fas fa-share"></a>
                        <button class="btn'; echo $dados_interp['nome']; echo'" data-clipboard-text="https://localhost/stringmusic/produto.php?p='; echo $dados_interp['nome']; echo '"><a class="fas fa-copy"></a></button>
                    </div>';?>
                    <script>
                        var button = document.getElementsByClassName("btn<?php echo $dados_interp['nome']?>");
                        new ClipboardJS(button);
                    </script>
                    <?php echo'
                        <img src="'; echo $dados_interp['imagem']; echo '" alt="">
                        <h3>'; 
                        if(strlen($dados_interp['nome']) > 14){
                        echo str_replace(substr($dados_interp['nome'], 11), '...', $dados_interp['nome']);
                        }
                        else{
                        echo $dados_interp['nome'];
                        } 
                        echo '</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="price"> R$'; echo $dados_interp['preco'];  echo '</div>
                        <form method="get" action="#">
                        <input type="text" name="p" style="display: none;" value="'; echo $dados_interp['nome']; echo '">
                        <input type="submit" class="btnpart" value="Visualizar">
                        </form>
                    </div>';
                }
                $busca_serv = "SELECT s.nm_servico as nome, s.vl_servico as preco, im.path as imagem from tb_compra as co join tb_carrinho as ca on ca.cd_carrinho = co.cd_carrinho join tb_servico as s on s.cd_servico = ca.cd_servico join tb_imagem as im on im.cd_imagem = s.cd_imagem where co.cd_usuario = '$session'";
                $query_serv = $mysqli->query($busca_serv);
                while($dados_serv = $query_serv->fetch_array()){
                    echo 
                    '<div class="box">
                        <div class="icons">
                        <a href="#" class="fas fa-share"></a>
                        <button class="btn'; echo $dados_serv['nome']; echo'" data-clipboard-text="https://localhost/stringmusic/prodserv.php?s='; echo $dados_serv['nome']; echo '"><a class="fas fa-copy"></a></button>
                    </div>';?>
                    <script>
                        var button = document.getElementsByClassName("btn<?php echo $dados_serv['nome']?>");
                        new ClipboardJS(button);
                    </script>
                    <?php echo'
                        <img src="'; echo $dados_serv['imagem']; echo '" alt="">
                        <h3>'; 
                        if(strlen($dados_serv['nome']) > 14){
                        echo str_replace(substr($dados_serv['nome'], 11), '...', $dados_serv['nome']);
                        }
                        else{
                        echo $dados_serv['nome'];
                        } 
                        echo '</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="price"> R$'; echo $dados_serv['preco'];  echo '</div>
                        <form method="get" action="#">
                        <input type="text" name="p" style="display: none;" value="'; echo $dados_serv['nome']; echo '">
                        <input type="submit" class="btnpart" value="Visualizar">
                        </form>
                    </div>';
                }
                $busca_inst = "SELECT s.nm_instrumento as nome, s.vl_instrumento as preco, im.path as imagem from tb_compra as co join tb_carrinho as ca on ca.cd_carrinho = co.cd_carrinho join tb_instrumento as s on s.cd_instrumento = ca.cd_instrumento join tb_imagem as im on im.cd_imagem = s.cd_imagem where co.cd_usuario = '$session'";
                $query_inst = $mysqli->query($busca_inst);
                while($dados_inst = $query_inst->fetch_array()){
                    echo 
                    '<div class="box">
                        <div class="icons">
                        <a href="#" class="fas fa-share"></a>
                        <button class="btn'; echo $dados_inst['nome']; echo'" data-clipboard-text="https://localhost/stringmusic/prodinst.php?i='; echo $dados_inst['nome']; echo '"><a class="fas fa-copy"></a></button>
                    </div>';?>
                    <script>
                        var button = document.getElementsByClassName("btn<?php echo $dados_inst['nome']?>");
                        new ClipboardJS(button);
                    </script>
                    <?php echo'
                        <img src="'; echo $dados_inst['imagem']; echo '" alt="">
                        <h3>'; 
                        if(strlen($dados_inst['nome']) > 14){
                        echo str_replace(substr($dados_inst['nome'], 11), '...', $dados_inst['nome']);
                        }
                        else{
                        echo $dados_inst['nome'];
                        } 
                        echo '</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="price"> R$'; echo $dados_inst['preco'];  echo '</div>
                        <form method="get" action="#">
                        <input type="text" name="i" style="display: none;" value="'; echo $dados_inst['nome']; echo '">
                        <input type="submit" class="btnpart" value="Visualizar">
                        </form>
                    </div>';
                }
            ?>
        </div>
        <?php } ?>
    </section>

<script>
    function menuAlterna(){
        const trocaMenu = document.querySelector('.menu');
        trocaMenu.classList.toggle('active');
    }
</script>
</body>
</html>