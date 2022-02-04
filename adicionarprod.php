<?php
session_start();
include ("conexao.php");

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
    <link rel="stylesheet" href="css/styleadicionar.css">
    <script src="script.js" defer></script>
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <!--SWAL-->
    <script src="swal.js"></script>

</head>
<!-- fiquei com preguiça de criar um .css e coloquei aqui xD-->
<style>

  .box{
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

<?php
  if(isset($_SESSION['produtoenviado'])) $onload = "produtoenviado";
  elseif(isset($_SESSION['size_stamp'])) $onload = "size_stamp";
  elseif(isset($_SESSION['error_stamp'])) $onload = "error_stamp";

  if(isset($_SESSION['produtoenviado'])):
  ?>
  <script>
    function produtoenviado(){
      Swal.fire({
        icon: 'success',
        text: 'Produto enviado com sucesso!',
        confirmButtonColor: '#32cd32',
        confirmButtonText: 'Prosseguir'
      }).then((result) => {
        if(result.isConfirmed){
          window.location.href = "loja.php";
        }
      })
    }
  </script>
  <?php
  endif;
  unset($_SESSION['produtoenviado']);

  if(isset($_SESSION['size_stamp'])):
  ?>
  <script>
    function size_stamp(){
      Swal.fire({
        icon: 'info',
        text: 'Sua imagem necessita ter no mínimo 1900px de largura por 2000px de altura e máximo de 1920px de largura por 2560px de altura!'
      })
    }
  </script>
  <?php
  endif;
  unset($_SESSION['size_stamp']);

  if(isset($_SESSION['error_stamp'])):
  ?>
  <script>
    function error_stamp(){
      Swal.fire({
        icon: 'info',
        text: 'Ocorreu um erro ao salvar sua imagem, tente novamente!'
      })
    }
  </script>
  <?php
  endif;
  unset($_SESSION['error_stamp']);
?>
<body onload="<?php echo $onload ?>()">

<!-- Ocorreu um erro ao colocar o css no Style original, por isso, criei um style interno -->
  <style>
        /* foto perfil */
        .action .profile{
          position: relative;
          width: 50px;
          height: 50px;
          border-radius: 50%;
          overflow: hidden;
          cursor: pointer;
        }

        .action .profile img{
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          object-fit: cover;
        }

        .action .menu{
          position: absolute;
          top: 120px;
          right: -28%;
          padding: 10px 20px;
          background-color: rgb(228, 228, 228);
          width: 200px;
          box-sizing: 0 5px 25px rgba(0,0,0,0.1);
          border-radius: 15px;
          transition: 0.5s;
          visibility: hidden;
          opacity: 0;
          z-index: 1;
        }

        .action .menu.active{
          top: 115%;
          visibility: visible;
          opacity: 1;
        }

        .action .menu::before{
          content: '';
          position: absolute;
          top: -5px;
          right: 28px;
          width: 20px;
          height: 20px;
          background-color: rgb(197, 197, 197);
          transform: rotate(45deg);
        }

        .action .menu h3{
          width: 100%;
          text-align: center;
          font-size: 18px;
          padding: 20px 0;
          font-weight: 500;
          color: #555;
          line-height: 1.2em;
        }

        .action .menu h3 span{
          font-size: 14px;
          color: #555;
          font-weight: 400;
        }

        .action .menu .un{
          padding-left: 0;
        }

        .action .menu .un li{
          list-style: none;
          padding: 10px 8px;
          border-top :1px solid rgba (0,0,0,0.05);
          display: flex;
          align-items: center;
        }

        .action .menu .un li img{
          max-width: 20px;
          margin-right: 10px;
          opacity: 0.5;
          transition: 0.5s;
        }

        .action .menu .un li:hover img{
          opacity: 1;
        }

        .action .menu .un li i{
          opacity: 0.5;
          transition: 0.5s;
        }

        .action .menu .un li:hover i{
          opacity: 1;
        }

        .action .menu .un li a{
          display: inline-block;
          text-decoration: none;
          color: #555;
          font-weight: 500;
          transition: 0.5s;
        }

        .action .menu .un .info:hover a{
          color: blue;
        }

        .action .menu .un .sair:hover a{
          color: red;
        }

        .box select{
          background: #4d84e2;
          color: #fff;
          padding: 10px;
          width: 150px;
          height: 50px;
          border: none;
          border-radius: 10px;
          font-size: 20px;
          box-shadow: 0 5px 25px;
          -webkit-appearance: button;
          outline: none;
          border-left: 10px;
        }
  </style>

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
 
    <form method="post" action="enviadoprod.php" enctype="multipart/form-data">
        <div class="container">
            <div class="contact-box">
                <div class="left">
                  <label>Escolha uma imagem de capa:</label>
                  <a href="#popup1">
                    <i class="fas fa-question-circle"></i>
                  </a>
                  <div id="popup1" class="overlay" style="z-index: 1">
                    <div class="popup">
                      <h3>Sobre as Imagens</h3>
                      <a class="close" href="#">&times;</a>
                      <div class="content">
                        <br>
                        <h5>
                          Para a publicação, as imagens devem ter dimensões: 
                        </h5>
                        <h5>
                          - Mínimo de 1900px de largura por 2000px de altura;
                        </h5>
                        <h5>
                          - Máximo de 1920px de largura por 2560px de altura.
                        </h5>
                      </div>
                    </div>
                  </div>
                  <input type="file" name="arquivo" id="arquivo" onchange="previewImagem()" accept="image/png, image/jpg, image/jpeg" class="field">
                  <br>
                  <br>
                  <img class="preview" style="width: 310px; height: 310px;"><br><br>
                </div>
                <div class="right">
                    <h2>Adicionar Produto</h2>
                    <label>Nome:</label>
                    <input type="text" name="nome" onkeypress="return Onlychars(event)" placeholder="Digite o nome do produto" class="field" id="nome" required>
                    <br>
                    <label>Descrição:</label>
                    <input type="text" name="desc" class="field" placeholder="Digite o que seu produto é" id="descricao" required>
                    <br>
                    <label>Quantidade:</label>
                    <input type="number" name="qtd" min="1" max="100" placeholder="Quantas unidades você venderá" onkeypress="return Onlynumbers(event)" class="field" id="quantidade" required>
                    <br>
                    <label>Preço:</label>
                    <input type="text" name="prc" onkeypress="return Only(event)" placeholder="Digite o preço do produto" class="field" id="valor" onkeyup="formatarMoeda()" required> 
                    <br>
                    <label>Gênero Musical:</label>
                    <div class="box">
                      <select name="genero_musical" id="genero_musical">
                        <option value="">Selecione</option>
                        <option value="jazz">Jazz</option>
                        <option value="mpb">MPB</option>
                        <option value="hm">Heavy Metal</option>
                        <option value="rap">Rap</option>
                        <option value="hh">Hip Hop</option>
                        <option value="rock">Rock</option>
                        <option value="pop">Pop</option>
                        <option value="blues">Blues</option>
                        <option value="funk">Funk</option>
                        <option value="elect">Eletrônica</option>
                        <option value="gosp">Gospel</option>
                        <option value="pag">Pagode</option>
                        <option value="samb">Samba</option>
                        <option value="folc">Folclórica</option>
                        <option value="erud">Erudita</option>
                        <option value="clas">Clássica</option>
                        <option value="sert">Sertanejo</option>
                        <option value="forr">Forró</option>
                        <option value="coun">Country</option>
                      </select>
                    </div>
                    <br>
                    <input type="submit" value="Enviar" class="btnpart">
                </div>
            </div>        
        </div>
    </form>

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
  
  <div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>

   

  <script>
    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
      arrow[i].addEventListener("click", (e)=>{
    let arrowParent = e.target.parentElement.parentElement;
    arrowParent.classList.toggle("showMenu");
      });
    }
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");
    console.log(sidebarBtn);
    sidebarBtn.addEventListener("click", ()=>{
      sidebar.classList.toggle("close");
    });
  </script>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
    function previewImagem(){
      var imagem = document.querySelector('input[name=arquivo]').files[0];
      var preview = document.querySelector('.preview');
        
      var reader = new FileReader();
        
      reader.onloadend = function () {
        preview.src = reader.result;
      }
        
      if(imagem){
        reader.readAsDataURL(imagem);
      }else{
        preview.src = "";
      }
    }
  </script>

  <!-- Menu navbar -> script interno -->
  <script>
            function menuAlterna(){
              const trocaMenu = document.querySelector('.menu');
              trocaMenu.classList.toggle('active');
            }
  </script>
          
  

</body>
</html>