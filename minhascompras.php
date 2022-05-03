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

    <div class="tab">
      <button class="tablinks" onclick="openTab(event, 'Part')">Partituras</button>
      <button class="tablinks" onclick="openTab(event, 'Inst')">Instrumentos</button>
      <button class="tablinks" onclick="openTab(event, 'Serv')">Serviços</button>
    </div>
    
    <div id="Part" class="tabcontent">
        
    <section class="product" id="product">
    <?php
        $busca_prod = "SELECT count(s.cd_interpretacao) as codigo from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_interpretacao as s on s.cd_interpretacao = c.cd_carrinho join tb_imagem as i on i.cd_imagem = s.cd_imagem where co.cd_usuario = '$codigousuario'";
        $busca_prod = $mysqli->query($busca_prod);
        $busca_prod = $busca_prod->fetch_assoc();
        $confirma_prod = $busca_prod['codigo'];
        if($confirma_prod == 0){
            echo '<h3 class="heading">Você não <span>possui compras de interpretações</span></h3>';
        }
        else{?>
        <div class="box-container">
            <?php
            $meusprods = "SELECT s.cd_interpretacao, s.nm_interpretacao, s.vl_interpretacao, i.path from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_interpretacao as s on s.cd_interpretacao = c.cd_carrinho join tb_imagem as i on i.cd_imagem = s.cd_imagem where co.cd_usuario = '$codigousuario'";
                $query = $mysqli->query($meusprods);
                while($dados = $query->fetch_array()){
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
                    if(strlen($dados['nm_interpretacao']) > 14){
                    echo str_replace(substr($dados['nm_interpretacao'], 11), '...', $dados['nm_interpretacao']);
                    }
                    else{
                    echo $dados['nm_interpretacao'];
                    } 
                    echo '</h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="price"> R$'; echo $dados['vl_interpretacao'];  echo '</div>
                    <button class="btnpart" onclick="viewprod'. $dados['cd_interpretacao'] .'()">Visualizar</button>
                </div>';
                ?>
                <script>
                    function viewprod<?php echo $dados['cd_interpretacao']?>(){
                        Swal.fire({
                            imageUrl: '<?php echo $dados['path'] ?>',
                            imageHeight: 300,
                            title: '<?php echo $dados['nm_interpretacao'] ?>'
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
            $busca_prod = "SELECT count(s.cd_instrumento) as codigo from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_instrumento as s on s.cd_instrumento = c.cd_carrinho join tb_imagem as i on i.cd_imagem = s.cd_imagem where co.cd_usuario = '$codigousuario'";
            $busca_prod = $mysqli->query($busca_prod);
            $busca_prod = $busca_prod->fetch_assoc();
            $confirma_prod = $busca_prod['codigo'];
            if($confirma_prod == 0){
                echo '<h3 class="heading">Você não <span>possui compras de instrumentos</span></h3>';
            }
            else{?>
            <div class="box-container">
                <?php
                $meusprods = "SELECT s.cd_instrumento, s.nm_instrumento, s.vl_instrumento, i.path from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_instrumento as s on s.cd_instrumento = c.cd_carrinho join tb_imagem as i on i.cd_imagem = s.cd_imagem where co.cd_usuario = '$codigousuario'";
                    $query = $mysqli->query($meusprods);
                    while($dados = $query->fetch_array()){
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
                        if(strlen($dados['nm_instrumento']) > 14){
                        echo str_replace(substr($dados['nm_instrumento'], 11), '...', $dados['nm_instrumento']);
                        }
                        else{
                        echo $dados['nm_instrumento'];
                        } 
                        echo '</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="price"> R$'; echo $dados['vl_instrumento'];  echo '</div>
                        <form method="get" action="view_prod.php">
                        <input type="text" name="p" style="display: none;" value="'; echo $dados['cd_instrumento']; echo '">
                        <input type="submit" class="btnpart" value="Visualizar">
                        </form>
                    </div>';
                    }
                ?>
            </div>
                
            <?php }?>
        </section>

    </div>
    
    <div id="Serv" class="tabcontent">
      
        <section class="product" id="product">
            <?php
                $busca_prod = "SELECT count(s.cd_servico) as codigo from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_servico as s on s.cd_servico = c.cd_carrinho join tb_imagem as i on i.cd_imagem = s.cd_imagem where co.cd_usuario = '$codigousuario'";
                $busca_prod = $mysqli->query($busca_prod);
                $busca_prod = $busca_prod->fetch_assoc();
                $confirma_prod = $busca_prod['codigo'];
                if($confirma_prod == 0){
                    echo '<h3 class="heading">Você não <span>possui compras de serviços</span></h3>';
                }
                else{?>
                <div class="box-container">
                    <?php
                    $meusprods = "SELECT s.cd_servico, s.nm_servico, s.vl_servico, i.path from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_servico as s on s.cd_servico = c.cd_carrinho join tb_imagem as i on i.cd_imagem = s.cd_imagem where co.cd_usuario = '$codigousuario'";
                        $query = $mysqli->query($meusprods);
                        while($dados = $query->fetch_array()){
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
                            if(strlen($dados['nm_servico']) > 14){
                            echo str_replace(substr($dados['nm_servico'], 11), '...', $dados['nm_servico']);
                            }
                            else{
                            echo $dados['nm_servico'];
                            } 
                            echo '</h3>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <div class="price"> R$'; echo $dados['vl_servico'];  echo '</div>
                            <form method="get" action="view_prod.php">
                            <input type="text" name="p" style="display: none;" value="'; echo $dados['cd_servico']; echo '">
                            <input type="submit" class="btnpart" value="Visualizar">
                            </form>
                        </div>';
                        }
                    ?>
                </div>
                    
                <?php }?>
            </section>

    </div>
    
    <!--
	<section class="item-container">
		<div class="item-img-section">
			<img class="item-img-LG" src="img/partitura.jpg" />
		</div>

		<div class="item-desc-section">
			<h2>Produto</h2>
			<h3>Nome do vendedor</h3>    
			<p class="description">Descrição sobre o produto</p>

			<div class="type">
				<button class="type-item">Descrição</button>
				<button class="type-item">Ticket da venda</button>
				<button class="type-item">Entrega</button>
			</div>
		</div>

	</section>-->

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