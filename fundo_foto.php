<?php
include ("conexao.php");
include ("verifica_login.php");
$session = $_SESSION['usuario'];
foreach($mysqli->query("SELECT cd_imagem AS confere FROM tb_usuario WHERE cd_usuario = '$session'") as $conferefoto){
    $semfoto = $conferefoto['confere'];
  }
foreach($mysqli->query("SELECT cd_fundo AS confere_fundo FROM tb_usuario WHERE cd_usuario = '$session'") as $conferefundo){
$semfundo = $conferefundo['confere_fundo'];
}

  if(empty($semfoto) && empty($semfundo)){
    foreach($mysqli->query(
    "SELECT us.nm_usuario AS nome, 
    us.nm_email AS email, 
    us.sg_especialidade AS especialidade,
    us.ds_usuario AS descricao, 
    DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
    u.sg_uf AS uf,
    cm.nm_cidade AS cidade
      FROM tb_usuario AS us JOIN tb_uf AS u
        ON u.cd_uf = us.cd_uf
          JOIN tb_cidade AS cm
            ON cm.cd_cidade = us.cd_cidade
              WHERE us.cd_usuario = '$session'") as $usuarios){
    $nomeusuario = $usuarios['nome'];
    $descricaousuario = $usuarios['descricao'];
    $emailusuario = $usuarios['email'];
    $nascimentousuario = $usuarios['nascimento'];
    $ufusuario = $usuarios['uf'];
    $cidadeusuario = $usuarios['cidade'];
    $imgusuario = "imgs/user.jpeg";
    $imgfundo = ": linear-gradient(45deg, #BC3CFF, #317FFF);";

    if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
    elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
    elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
    }
  }
  elseif (empty($semfoto)){
    foreach($mysqli->query(
      "SELECT us.nm_usuario AS nome, 
      us.nm_email AS email, 
      us.sg_especialidade AS especialidade,
      us.ds_usuario AS descricao, 
      DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
      u.sg_uf AS uf,
      cm.nm_cidade AS cidade,
      (SELECT im.path FROM tb_fundo as f 
      	JOIN tb_imagem as im
      	  ON im.cd_imagem = f.cd_imagem
       		JOIN tb_usuario as us
       		  ON us.cd_fundo = f.cd_fundo
      			WHERE us.cd_usuario = '$session') AS fundo
        FROM tb_usuario AS us JOIN tb_uf AS u
          ON u.cd_uf = us.cd_uf
            JOIN tb_cidade AS cm
              ON cm.cd_cidade = us.cd_cidade
                WHERE us.cd_usuario = '$session'") as $usuarios){
      $nomeusuario = $usuarios['nome'];
      $descricaousuario = $usuarios['descricao'];
      $emailusuario = $usuarios['email'];
      $nascimentousuario = $usuarios['nascimento'];
      $ufusuario = $usuarios['uf'];
      $cidadeusuario = $usuarios['cidade'];
      $imgusuario = "imgs/user.jpeg";
      $imgfundo = "-image: url(" . $usuarios['fundo'] . ")";
  
      if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
      elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
      elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
      }
  }
  elseif(empty($semfundo)){
    foreach($mysqli->query(
      "SELECT us.nm_usuario AS nome, 
      us.nm_email AS email, 
      us.sg_especialidade AS especialidade, 
      us.ds_usuario AS descricao, 
      DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
      u.sg_uf AS uf,
      cm.nm_cidade AS cidade,
      i.path AS path
        FROM tb_usuario AS us JOIN tb_uf AS u
          ON u.cd_uf = us.cd_uf
            JOIN tb_imagem AS i
              ON i.cd_imagem = us.cd_imagem
                JOIN tb_cidade AS cm
                  ON cm.cd_cidade = us.cd_cidade
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
      u.sg_uf AS uf,
      cm.nm_cidade AS cidade,
      i.path AS path,
      (SELECT im.path FROM tb_fundo as f 
      	JOIN tb_imagem as im
      	  ON im.cd_imagem = f.cd_imagem
       		JOIN tb_usuario as us
       		  ON us.cd_fundo = f.cd_fundo
      			WHERE us.cd_usuario = '$session') AS fundo
        FROM tb_usuario AS us JOIN tb_uf AS u
          ON u.cd_uf = us.cd_uf
            JOIN tb_imagem AS i
              ON i.cd_imagem = us.cd_imagem
                JOIN tb_cidade AS cm
                  ON cm.cd_cidade = us.cd_cidade
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