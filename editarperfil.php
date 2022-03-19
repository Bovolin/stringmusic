<?php
include ("fundo_foto.php");

$session = $_SESSION['usuario'];

foreach($mysqli->query("SELECT cd_imagem AS confere FROM tb_usuario WHERE cd_usuario = '$session'") as $conferefoto){
  $semfoto = $conferefoto['confere'];
}

if(empty($semfoto)){
    foreach($mysqli->query(
    "SELECT us.nm_usuario AS nome, 
    us.nm_email AS email,
    us.nm_cep AS cep,
    us.nm_endereco AS rua,
    us.sg_especialidade AS especialidade,
    us.ds_usuario AS descricao, 
    DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
    u.sg_uf AS uf,
    b.nm_bairro AS bairro,
    cm.nm_cidade AS cidade
        FROM tb_usuario AS us JOIN tb_uf AS u
            ON u.cd_uf = us.cd_uf
                JOIN tb_cidade AS cm
                    ON cm.cd_cidade = us.cd_cidade
                        JOIN tb_bairro AS b
                            ON b.cd_bairro = us.cd_bairro
                                WHERE us.cd_usuario = '$session'") as $usuarios){
    $nomeusuario = $usuarios['nome'];
    $descricaousuario = $usuarios['descricao'];
    $emailusuario = $usuarios['email'];
    $nascimentousuario = $usuarios['nascimento'];
    $ufusuario = $usuarios['uf'];
    $ruausuario = $usuarios['rua'];
    $bairrousuario = $usuarios['bairro'];
    $cepusuario = $usuarios['cep'];
    $cidadeusuario = $usuarios['cidade'];
    $imgusuario = "imgs/user.jpeg";

    if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
    elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
    elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
    }
}
else{
    foreach($mysqli->query(
        "SELECT us.nm_usuario AS nome, 
        us.nm_email AS email,
        us.nm_cep AS cep,
        us.nm_endereco AS rua,
        us.sg_especialidade AS especialidade, 
        us.ds_usuario AS descricao, 
        DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
        u.sg_uf AS uf,
        b.nm_bairro AS bairro,
        cm.nm_cidade AS cidade,
        i.path AS path
        FROM tb_usuario AS us JOIN tb_uf AS u
            ON u.cd_uf = us.cd_uf
            JOIN tb_imagem AS i
            ON i.cd_imagem = us.cd_imagem
                JOIN tb_cidade AS cm
                ON cm.cd_cidade = us.cd_cidade
                    JOIN tb_bairro AS b
                    ON b.cd_bairro = us.cd_bairro
                        WHERE us.cd_usuario = '$session'") as $usuarios){
        $nomeusuario = $usuarios['nome'];
        $descricaousuario = $usuarios['descricao'];
        $emailusuario = $usuarios['email'];
        $ruausuario = $usuarios['rua'];
        $cepusuario = $usuarios['cep'];
        $nascimentousuario = $usuarios['nascimento'];
        $ufusuario = $usuarios['uf'];
        $bairrousuario = $usuarios['bairro'];
        $cidadeusuario = $usuarios['cidade'];
        $imgusuario = $usuarios['path'];
    
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
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
        <title>StringMusic</title>
        <!--ViaCEP-->
        <script>
        
            function limpa_formulário_cep() {
                    //Limpa valores do formulário de cep.
                    document.getElementById('rua').value=("");
                    document.getElementById('bairro').value=("");
                    document.getElementById('cidade').value=("");
                    document.getElementById('uf').value=("");
            }

            function meu_callback(conteudo) {
                if (!("erro" in conteudo)) {
                    //Atualiza os campos com os valores.
                    document.getElementById('rua').value=(conteudo.logradouro);
                    document.getElementById('bairro').value=(conteudo.bairro);
                    document.getElementById('cidade').value=(conteudo.localidade);
                    document.getElementById('uf').value=(conteudo.uf);
                } //end if.
                else {
                    //CEP não Encontrado.
                    limpa_formulário_cep();
                    alert("CEP não encontrado.");
                }
            }
                
            function pesquisacep(valor) {

                //Nova variável "cep" somente com dígitos.
                var cep = valor.replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        document.getElementById('rua').value="...";
                        document.getElementById('bairro').value="...";
                        document.getElementById('cidade').value="...";
                        document.getElementById('uf').value="...";

                        //Cria um elemento javascript.
                        var script = document.createElement('script');

                        //Sincroniza com o callback.
                        script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                        //Insere script no documento e carrega o conteúdo.
                        document.body.appendChild(script);

                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            };

        </script>
    </head>

    <body>
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
                            <li class="info"><i class="bx bxs-user-detail"></i><a href="editarperfil.php">Editar Perfil</a></li>
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
                        <form action="editar.php" method="post">
                        <input placeholder="Insira uma descrição sobre você:" type="text" name="descricao" style="width: 345px;" value="<?php echo $descricaousuario?>" class="field">
                    </div>
                    <div class="perfil-usuario-footer">
                        <ul class="dados">
                            <li><i class="icono fas fa-map-marked-alt" aria-hidden="true"> Localização: </i></li>
                            <input class="field" type="text" name="cep" id="cep" onblur="pesquisacep(this.value);" value="<?php echo $cepusuario?>" required>
                            <input class="field" type="text" name="cidade" id="cidade" value="<?php echo $cidadeusuario?>" required>
                            <input class="field" type="text" name="uf" id="uf" value="<?php echo $ufusuario?>" required>
                            <input class="field" type="text" name="rua" id="rua" value="<?php echo $ruausuario?>" required>
                            <input class="field" type="text" name="bairro" id="bairro" value="<?php echo $bairrousuario?>" required>
                            <li><i class="icono fas fa-envelope"> Email para contato: </i></li>
                            <input class="field" type="text" name="email" value="<?php echo $emailusuario?>" required>
                            <li><i class="fas fa-key"></i> Insira sua senha de login:</li>
                            <input class="field" type="password" name="senha" required>
                            <?php
                            if(isset($_SESSION['senha_incorreta'])):
                            ?>
                            <li style="color: red">A senha informada está incorreta!</li>
                            <?php
                            endif;
                            unset($_SESSION['senha_incorreta']);
                            ?>
                            <li><i class="fas fa-unlock-alt"></i> Confirmar Senha: </li>
                            <input class="field" type="password" name="confirma_senha" required>
                            <?php
                            if(isset($_SESSION['dupla_senha'])):
                            ?>
                            <li style="color: red">As senhas não são iguais!</li>
                            <?php
                            endif;
                            unset($_SESSION['dupla_senha']);
                            ?>
                            <li><i class="icono fa fa-calendar" aria-hidden="true"> Ano de nascimento: </i></li>
                            <?php echo $nascimentousuario?>
                            <li><i class="icono fas fa-music"> Tipo de usuário: </i></li>
                            <?php echo $especialidadeusuario?>
                        </ul>
                    </div>
                    <a href=""><input style="margin-top: 10px;" type="submit" value="Salvar" class="btnpart"></a>
                    <a href="painel.php"><input type="button" value="Não Salvar" class="btnpart2"></a>
                </form>
            </div>
        </section>

    <script>
        document.getElementById("file").onchange = function() {
            document.getElementById("form").submit();
        };
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