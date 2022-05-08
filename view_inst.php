<?php
    session_start();
    include ("conexao.php");

    $c = $_GET['s'];

    foreach($mysqli->query("SELECT i.cd_usuario AS codigousuario, i.nm_instrumento AS instrumento, i.ds_instrumento AS descricao, i.vl_instrumento AS valor, u.nm_usuario AS nomeusuario, u.ds_usuario AS descricaousuario, im.path AS pathh FROM tb_instrumento AS i JOIN tb_usuario AS u ON u.cd_usuario = i.cd_usuario JOIN tb_imagem AS im ON im.cd_imagem = i.cd_imagem WHERE i.cd_instrumento = '$c'") as $instrumento){
    $nome_servico = $instrumento['instrumento'];
    $descricao_servico = $instrumento['descricao'];
    $nomedono = $instrumento['nomeusuario'];
    $descricaousuario = $instrumento['descricaousuario'];
    $valor_servico = $instrumento['valor'];
    $path = $instrumento['pathh'];
    $codigouser = $instrumento['codigousuario'];
    }
    foreach($mysqli->query("SELECT i.path AS path FROM tb_imagem AS i JOIN tb_usuario AS u ON i.cd_imagem = u.cd_imagem WHERE u.cd_usuario = '$codigouser'") as $pegaimagem){
        $imagemdono = $pegaimagem['path'];
    }


    if(isset($_SESSION['usuario'])){
    $session = $_SESSION['usuario'];

    foreach($mysqli->query("SELECT u.nm_usuario AS nome, u.sg_especialidade AS especialidade, i.path AS path FROM tb_usuario AS u JOIN tb_imagem AS i ON i.cd_imagem = u.cd_imagem WHERE cd_usuario = '$session'") as $usuarios){
        $nomeusuario = $usuarios['nome'];
        $imagemusuario = $usuarios['path'];
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
    <link rel="stylesheet" href="css/styleadicionars.css">
    <script src="js/script.js" defer></script>
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <!--SWAL-->
    <script src="js/swal.js"></script>

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
            <option value="instrumentos.php" selected>Instrumentos</option>
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
                  <div class="menu" style="right: -2% !important;">
                    <h3>'; echo $nomeusuario; echo '<br><span>'; echo $especialidadeusuario; echo '</span></h3>
                      <ul class="un">
                        <li class="info"><i class="bx bx-user-circle"></i><a href="painel.php">Meu Perfil</a></li>
                        <li class="info"><i class="bx bx-cart"></i><a href="mercadopag/view/mercadopag.php">Carrinho</a></li>
                        <li class="info"><i class="bx bx-envelope"></i><a href="meusprodutos.php">Meus Produtos</a></li>
                        <li class="info"><i class="bx bx-notepad"></i><a href="minhascompras.php">Minhas Compras</a></li>
                        <li class="info"><i class="bx bx-package"></i><a href=""vendidos.php>Minhas Vendas</a></li>
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

    <form action="altera_inst.php" method="post">
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
                        <h1 style="color: var(--color-headings)"><?php echo $nome_servico?></h1>
                        <input type="text" value="<?php echo $valor_servico ?>" name="prc" onkeypress="return Only(event)" placeholder="Digite o preço do produto" class="field" id="valor" onkeyup="formatarMoeda()" required>
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
                    <input type="text" name="desc" value="<?php echo $descricao_servico ?>" class="field" placeholder="Digite o que seu produto é" id="descricao" required>
                    <input type="text" value="<?php echo $c ?>" name="codigo" style="display: none;">
                    <button type="submit" name="alterar" value="a" class="btnpart" style="width: 200px;">
                        Modificar <i class="fas fa-arrow-alt-circle-right"></i>
                    </button>
                    <br>
                    <button type="submit" name="remover" class="btnpart" style="width: 200px;">
                        Remover <i class="fas fa-trash"></i>
                    </button> 
                    <br>
                    <button type="submit" class="btnpart2" name="cancelar" style="width: 200px">
                        Cancelar
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