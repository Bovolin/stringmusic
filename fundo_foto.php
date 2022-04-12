<?php
include ("conexao.php");
include ("verifica_login.php");
$session = $_SESSION['usuario'];
foreach($mysqli->query("SELECT cd_fundo AS confere_fundo FROM tb_usuario WHERE cd_usuario = '$session'") as $conferefundo){
  $semfundo = $conferefundo['confere_fundo'];
}

  if(empty($semfundo)){
    foreach($mysqli->query(
      "SELECT us.nm_usuario AS nome, 
      us.nm_email AS email, 
      us.sg_especialidade AS especialidade, 
      us.ds_usuario AS descricao, 
      DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
      e.sg_uf AS uf,
      e.nm_cidade AS cidade,
      i.path AS path
        FROM tb_usuario AS us
          JOIN tb_endereco AS e
            ON e.cd_endereco = us.cd_endereco
              JOIN tb_imagem AS i
              ON i.cd_imagem = us.cd_imagem
                WHERE us.cd_usuario = '$session'") as $usuarios){
      $nomeusuario = $usuarios['nome'];
      $descricaousuario = $usuarios['descricao'];
      $emailusuario = $usuarios['email'];
      $nascimentousuario = $usuarios['nascimento'];
      $ufusuario = $usuarios['uf']; 
      $cidadeusuario = $usuarios['cidade'];
      $imgusuario = $usuarios['path'];
      $imgfundo = ": linear-gradient(45deg, #BC3CFF, #317FFF);";

      if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
      elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
      elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
      }
  }
  else{
    foreach($mysqli->query(
      "SELECT us.nm_usuario AS nome, 
      us.nm_email AS email, 
      us.sg_especialidade AS especialidade, 
      us.ds_usuario AS descricao, 
      DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
      e.sg_uf AS uf,
      e.nm_cidade AS cidade,
      i.path AS path,
      (SELECT im.path FROM tb_fundo as f 
      	JOIN tb_imagem as im
      	  ON im.cd_imagem = f.cd_imagem
       		JOIN tb_usuario as us
       		  ON us.cd_fundo = f.cd_fundo
      			WHERE us.cd_usuario = '$session') AS fundo
        FROM tb_usuario AS us
          JOIN tb_endereco AS e
            ON e.cd_endereco = us.cd_endereco 
              JOIN tb_imagem AS i
                ON i.cd_imagem = us.cd_imagem
                  WHERE us.cd_usuario = '$session'") as $usuarios){
      $nomeusuario = $usuarios['nome'];
      $descricaousuario = $usuarios['descricao'];
      $emailusuario = $usuarios['email'];
      $nascimentousuario = $usuarios['nascimento'];
      $ufusuario = $usuarios['uf']; 
      $cidadeusuario = $usuarios['cidade'];
      $imgusuario = $usuarios['path'];
      $imgfundo = "-image: url(" . $usuarios['fundo'] . ")";

      if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
      elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
      elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
    }
}
?>