<?php
if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha'])){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
}
else{
    $nome = '';
    $email = '';
    $senha = '';
}

session_start();

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
        <script src="swal.js"></script>
        <link rel="stylesheet" href="css/styleconcluir.css" />
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
    <?php
        if(isset($_SESSION['cpf_invalido'])) $swal = 'cpf_invalido';
        elseif(isset($_SESSION['usuario_existe'])) $swal = 'usuario_existe';
        elseif(isset($_SESSION['cpf_cadastrado'])) $swal = 'cpf_cadastrado';

        if(isset($_SESSION['usuario_existe'])):
    ?>
        <script>
            function usuario_existe(){
                Swal.fire({
                    icon: 'error',
                    title: 'Credenciais já registradas!'
                })
            }
        </script>
    <?php
        endif;
        unset($_SESSION['usuario_existe']);
    ?>
    <?php
        if(isset($_SESSION['cpf_invalido'])):
    ?>
        <script>
            function cpf_invalido(){
                Swal.fire({
                    icon:'error',
                    title: 'CPF inválido'
                })
            }
        </script>
    <?php
        endif;
        unset($_SESSION['cpf_invalido']);
    ?>
    <?php
        if(isset($_SESSION['cpf_cadastrado'])):
    ?>
        <script>
            function cpf_cadastrado(){
                Swal.fire({
                    icon: 'error',
                    title: 'CPF já cadastrado'
                })
            }
        </script>
    <?php
        endif;
        unset($_SESSION['cpf_cadastrado'])
    ?>

    <body onload="<?php echo $swal?>()">
        <div class="container">
            <div class="forms-container">
                <div class="signin-signup">
                    <form action="efetuado.php" method="post" onsubmit="return alerta(this);" class="sign-in-form">
                        <h2 class="title">Falta só um pouco!</h2>
                        <div class="input-field">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            <input type="date" name="data" placeholder="Data de Nascimento" required>
                        </div>
                        <div class="input-field">
                            <i class="fa fa-address-card" aria-hidden="true"></i>
                            <input type="text" name="cpf" oninput="mascara(this)" placeholder="Insira seu CPF" required>
                        </div>
                        <div class="input-field">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <input type="text" name="cep" id="cep" onblur="pesquisacep(this.value);" placeholder="Insira seu CEP" required>
                        </div>
                        <div class="input-field">
                            <i class="fa fa-university" aria-hidden="true"></i>
                            <input type="text" name="uf" id="uf" placeholder="Insira seu estado" required>
                        </div>
                        <div class="input-field">
                            <i class="fas fa-building"></i>
                            <input type="text" name="cidade" id="cidade" placeholder="Insira sua cidade" required>
                        </div>
                        <div class="input-field">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <input type="text" name="rua" id="rua" placeholder="Insira sua rua" required>
                        </div>
                        <div class="input-field">
                            <i class="fas fa-laptop-house"></i>
                            <input type="text" name="bairro" id="bairro" placeholder="Insira sua bairro" required>
                        </div>
                        <div class="box">
                            <select name="genero" id="genero">
                                <option value="m">Masculino</option>
                                <option value="f">Feminino</option>
                                <option value="n">Não Binário</option>
                                <option value="p">Prefiro não dizer</option>
                            </select>
                        
                            <select name="especialidade" id="especialidade">
                                <option value="m">Músico</option>
                                <option value="c">Compositor</option>
                                <option value="v">Visitante</option>
                            </select>
                        </div>

                        <input type="submit" value="Concluir cadastro" class="btn">
                </div>
            </div>

            <div class="panels-container">
                <div class="panel left-panel">
                    <div class="content">
                        <h3>Deseja alterar alguma informação?</h3>
                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" name="nome" placeholder="Insira seu nome completo" value="<?=$nome?>" required>
                        </div>
                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="senha" placeholder="Insira sua senha" value="<?=$senha?>" required>
                        </div>
                        <div class="input-field">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="Insira seu email" value="<?=$email?>" required>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        

<!--Máscara de CPF-->
<script>
    function mascara(i){
   
        var v = i.value;
        
        if(isNaN(v[v.length-1])){ // impede entrar outro caractere que não seja número
            i.value = v.substring(0, v.length-1);
            return;
        }
        
        i.setAttribute("maxlength", "14");
        if (v.length == 3 || v.length == 7) i.value += ".";
        if (v.length == 11) i.value += "-";

    }
</script>

<!--SWAL-->
<script>
    function alerta(form){
        Swal.fire({
            icon: 'info',
            title: 'Termos de Serviço - Privacidade & Termos',
            text: '...',
            confirmButtonText: 'Eu li e concordo com os Termos de Serviço'
        })
        .then((isOkay)=> {
            if (isOkay) form.submit();
        });
        return false;
    }
</script>

    </body>
</html>