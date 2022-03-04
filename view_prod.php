<?php
    session_start();
    include ("conexao.php");

    $c = $_GET['p'];

    foreach($mysqli->query("SELECT i.cd_usuario AS codigousuario, i.nm_interpretacao AS interpretacao, i.ds_interpretacao AS descricao, i.qt_interpretacao AS quantidade, i.vl_interpretacao AS valor, u.nm_usuario AS nomeusuario, u.ds_usuario AS descricaousuario, im.path AS pathh FROM tb_interpretacao AS i JOIN tb_usuario AS u ON u.cd_usuario = i.cd_usuario JOIN tb_imagem AS im ON im.cd_imagem = i.cd_imagem WHERE i.cd_interpretacao = '$c'") as $interpretacao){
    $nomeinterpretacao = $interpretacao['interpretacao'];
    $descricaointerpretacao = $interpretacao['descricao'];
    $quantidadeinterpretacao = $interpretacao['quantidade'];
    $nomedono = $interpretacao['nomeusuario'];
    $descricaousuario = $interpretacao['descricaousuario'];
    $valorinterpretacao = $interpretacao['valor'];
    $path = $interpretacao['pathh'];
    $codigouser = $interpretacao['codigousuario'];
    }
    foreach($mysqli->query("SELECT cd_imagem AS conferefoto FROM tb_usuario WHERE cd_usuario = '$codigouser'") as $confere_foto_dono){
    $dono_sem_foto = $confere_foto_dono['conferefoto'];
    }
    if(empty($dono_sem_foto)){
    $imagemdono = "imgs/user.png";
    }
    else{
    foreach($mysqli->query("SELECT i.path AS path FROM tb_imagem AS i JOIN tb_usuario AS u ON i.cd_imagem = u.cd_imagem WHERE u.cd_usuario = '$codigouser'") as $pegaimagem){
        $imagemdono = $pegaimagem['path'];
    }
    }


    if(isset($_SESSION['usuario'])){
    $session = $_SESSION['usuario'];

    foreach($mysqli->query("SELECT cd_imagem AS confere FROM tb_usuario WHERE cd_usuario = '$session'") as $conferefoto){
        $semfoto = $conferefoto['confere'];
    }

    if(empty($semfoto)){
        foreach($mysqli->query("SELECT u.nm_usuario AS nome, u.sg_especialidade AS especialidade FROM tb_usuario AS u WHERE cd_usuario = '$session'") as $usuarios){
        $nomeusuario = $usuarios['nome'];
        $imagemusuario = "imgs/user.png";
        if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
        elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
        elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
        }
    }
    else{
        foreach($mysqli->query("SELECT u.nm_usuario AS nome, u.sg_especialidade AS especialidade, i.path AS path FROM tb_usuario AS u JOIN tb_imagem AS i ON i.cd_imagem = u.cd_imagem WHERE cd_usuario = '$session'") as $usuarios){
        $nomeusuario = $usuarios['nome'];
        $imagemusuario = $usuarios['path'];
        if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
        elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
        elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
        }
    }
    }

?>

<!DOCTYPE html>
<html lang="pt-BR" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>StringMusic</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styleadicionars.css">
    <script src="script.js" defer></script>
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <!--SWAL-->
    <script src="swal.js"></script>

</head>

<body>

  <header>
    <nav style="background: #2d2a30;">
      <ul class="ul-nav">
        <li class="logo"><a href="index.php"><img src="imgs/LogoAqui.png" alt="logo do site"></a></li>
        <li class="items"><a href="index.php">Início</a></li>
        <li class="items"><a href="loja.php">Produtos</a></li>
        <li class="items"><a href="servico.php">Serviços</a></li>
        <li class="items"><a href="contato.php">Contatos</a></li>
        <?php
          if(isset($_SESSION['usuario'])){
            echo
            '<li class="items">
              <div class="action">
                <div class="profile" onclick="menuAlterna();">
                    <img src="'; echo $imagemusuario; echo'">
                </div>
                <div class="menu">
                  <h3>'; echo $nomeusuario; echo '<br><span>'; echo $especialidadeusuario; echo '</span></h3>
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
            echo '<li class="items"><a href="login.php">Login</a></li>';
          }
          ?>
        <li class="btn"><a href="#"><i class="fas fa-bars"></i></a></li>
      </ul>
    </nav>

     <!-- NÃO TOCA AQUI PELO AMOR DE DEUS \/-->  
     <script>
        /*  $(document).ready(function(){
          $('.btn').click(function(){
            $('.items').toggleClass("show");
            $('ul li').toggleClass("hide");
          });
        }); */
        const btn = document.getElementsByClassName('btn')[0];
          btn.addEventListener('click', function() {
          let items = document.getElementsByClassName('items');
          for (let i = 0; i <= items.length - 1; i += 1) {
            if (items[i].classList.contains('show')) {
              items[i].classList.remove('show');
            } else {
              items[i].classList.add('show');
            }         
          }
        });
     </script> 
  </header>

    <form action="altera_prod.php" method="post">
        <div class="container">
            <div class="contact-box">
                <div class="left">
                    <div class="imagem-produto">
                    <img src="<?php echo $path ?>" style="width: 425px; height: 570px;">
                    </div>
                </div>
                <div class="right">
                    <div class="info-produto">
                    <div class="espec-produto">
                        <h1 style="color: var(--color-headings)"><?php echo $nomeinterpretacao?></h1>
                        
                        <input type="text" value="<?php echo $valorinterpretacao ?>" name="prc" onkeypress="return Only(event)" placeholder="Digite o preço do produto" class="field" id="valor" onkeyup="formatarMoeda()" required>
                        <h4>
                        <a href="#popup1" style="color: var(--color-headings)" style="color: black;">
                            <?php echo $nomedono ?>
                        </a>
                        </h4>            
                    </div>
                    <div class="avalia-produto">
                        <!-- em breve: rate por estrela -->
                    </div>
                    <h4 style="color: var(--color-text)">Descrição:</h4>
                    <input type="text" name="desc" value="<?php echo $descricaointerpretacao ?>" class="field" placeholder="Digite o que seu produto é" id="descricao" required>
                    <h5 style="color: var(--color-text)">Quantidade: <?php echo $quantidadeinterpretacao ?></h5>
                        <input type="text" value="<?php echo $c ?>" name="codigo" style="display: none;">
                        <button type="submit" name="alterar" value="a" class="btnpart" style="width: 200px;">
                            Modificar <i class="fas fa-arrow-alt-circle-right"></i>
                        </button>
                        <br>
                        <br>
                        <button type="submit" name="remover" value="r" class="btnpart" style="width: 200px;">
                            Remover <i class="fas fa-trash"></i>
                        </button> 
                </div>
            </div>  
          
          </form>    
          <div id="popup1" class="overlay">
            <div class="popup" style="background-color: var(--bg-panel)">
              <h3 style="color: var(--color-headings)"><?php echo $nomedono ?></h3>
              <a class="close" href="#">&times;</a>
                <div class="content">
                  <img src="<?php echo $imagemdono ?>" alt="foto de usuário" style="width: 200px; height: 200px;border-radius: 190px;">
                    <br>
                    <br>
                    <h4 style="color: var(--color-text)"><?php echo $descricaousuario ?></h4>
                    <form action="userpreview.php" method="get">
                      <input type="text" style="display: none;" name="u" value="<?php echo $codigouser?>">
                      <input type="submit" class="btnpart" value="Visualizar Perfil"></input>
                    </form>
                </div>
            </div>
          </div>          
        </div>
        
        </div>
    
    

    <footer class="footer-distributed">

        <div class="footer-left">
            <h3>String<span>Music</span></h3>

            <p class="footer-links">
                <a href="index.php">Início</a>
                |
                <a href="contato.php">Contatos</a>
                |
                <a href="login.php">Login</a>
            </p>

            <p class="footer-company-name">Copyright © 2021 <strong>StringMusic</strong>
            <p style="color: white;">Todos os direitos reservados</p>
        </div>

        <div class="footer-center">
            <div>
                <i class="fa fa-map-marker"></i>
                <p><span>ETEC Dra Ruth Cardoso</span>
                    São Vicente / SP</p>
            </div>

            <div>
                <i class="fa fa-phone"></i>
                <p>+55 (13) 5555-5555</p>
            </div>
            <div>
                <i class="fa fa-envelope"></i>
                <p><a href="#">stringmsc@email.com</a></p>
            </div>
        </div>
        <div class="footer-right">
            <p class="footer-company-about">
                <span>Sobre nós</span>
                <strong>StringMusic</strong>
            </p>
            <div class="footer-icons">
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-instagram"></i></a>
                <a href="#"><i class="fa fa-youtube"></i></a>
            </div>
        </div>
    </footer>

    <style>
      /* footer */
      footer {
        position: fixed;
        bottom: 0;
      }

      @media (max-height:800px) {
        footer {
            position: static;
        }
      }

      .footer-distributed {
        background-color: #2d2a30;
        box-sizing: border-box;
        width: 100%;
        text-align: left;
        font: bold 16px sans-serif;
        padding: 50px 50px 60px 50px;
        margin-top: 80px;
      }

      .footer-distributed .footer-left, .footer-distributed .footer-center, .footer-distributed .footer-right {
        display: inline-block;
        vertical-align: top;
      }

      /* Footer left */

      .footer-distributed .footer-left {
        width: 30%;
      }

      .footer-distributed h3 {
        color: #ffffff;
        font: normal 36px 'Cookie', cursive;
        margin: 0;
      }


      .footer-distributed h3 span {
        color: #3F71EA;
      }

      /* Footer links */

      .footer-distributed .footer-links {
        color: #ffffff;
        margin: 20px 0 12px;
      }

      .footer-distributed .footer-links a {
        display: inline-block;
        line-height: 1.8;
        text-decoration: none;
        color: inherit;
      }

      .footer-distributed .footer-company-name {
        color: #8f9296;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
      }


      /* Footer Center */

      .footer-distributed .footer-center {
        width: 35%;
      }

      .footer-distributed .footer-center i {
        background-color: #33383b;
        color: #ffffff;
        font-size: 25px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        text-align: center;
        line-height: 42px;
        margin: 10px 15px;
        vertical-align: middle;
      }

      .footer-distributed .footer-center i.fa-envelope {
        font-size: 17px;
        line-height: 38px;
      }

      .footer-distributed .footer-center p {
        display: inline-block;
        color: #ffffff;
        vertical-align: middle;
        margin: 0;
      }

      .footer-distributed .footer-center p span {
        display: block;
        font-weight: normal;
        font-size: 14px;
        line-height: 2;
      }

      .footer-distributed .footer-center p a {
        color: #3F71EA;
        text-decoration: none;
        ;
      }

      /* Footer Right */

      .footer-distributed .footer-right {
        width: 30%;
      }

      .footer-distributed .footer-company-about {
        line-height: 20px;
        color: #92999f;
        font-size: 13px;
        font-weight: normal;
        margin: 0;
      }

      .footer-distributed .footer-company-about span {
        display: block;
        color: #ffffff;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
      }

      .footer-distributed .footer-icons {
        margin-top: 25px;
      }

      .footer-distributed .footer-icons a {
        display: inline-block;
        width: 35px;
        height: 35px;
        cursor: pointer;
        background-color: #33383b;
        border-radius: 2px;
        font-size: 20px;
        color: #ffffff;
        text-align: center;
        line-height: 35px;
        margin-right: 3px;
        margin-bottom: 5px;
      }

      .footer-distributed .footer-icons a:hover {
        background-color: #3F71EA;
      }

      .footer-links a:hover {
        color: #3F71EA;
      }

      @media (max-width: 880px) {
        .footer-distributed .footer-left, .footer-distributed .footer-center, .footer-distributed .footer-right {
            display: block;
            width: 100%;
            margin-bottom: 40px;
            text-align: center;
        }
        .footer-distributed .footer-center i {
            margin-left: 0;
        }
      }     
    </style>

  <!-- Menu navbar -> script interno -->
  <script>
            function menuAlterna(){
              const trocaMenu = document.querySelector('.menu');
              trocaMenu.classList.toggle('active');
            }
  </script>

  <!-- fiquei com preguiça de criar um .css e coloquei aqui xD-->
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