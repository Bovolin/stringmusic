<?php

require_once('lib/Facebook/autoload.php');
//unset($_SESSION['face_access_token']);
//Conexão ao SDK
$fb = new \Facebook\Facebook([
    'app_id' => '662282304883646',
    'app_secret' => '186d716e6a4d3df2d20a6db5d4516fdc',
    'default_graph_version' => 'v2.9',
    //'default_access_token' => '{access-token}', // optional
]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email'];

try{
    if(isset($_SESSION['face_access_token'])) $accessToken = $_SESSION['face_access_token'];
    else $accessToken = $helper->getAccessToken();
}
//Caso não concorde com os termos
catch(Facebook\Exceptions\FacebookResponseException $e){
    echo $e->getMessage();
    exit;
}
//Caso dê erro no SDK
catch(Facebook\Exceptions\FacebookSDKException $e){
    echo 'Ocorreu um erro no SDK do Facebook: ' . $e->getMessage();
    exit;
}

if(!isset($accessToken)){
    $url_login = 'https://localhost/stringmusic/login.php';
    $loginUrl = $helper->getLoginUrl($url_login, $permissions);
}
else{
    $url_login = 'https://localhost/stringmusic/login.php';
    $loginUrl = $helper->getLoginUrl($url_login, $permissions);
    //Autenticado
    if(isset($_SESSION['face_access_token'])){
        $fb->setDefaultAccessToken('face_access_token');
    }
    //Não Autenticado
    else{
        $_SESSION['face_access_token'] = (string) $accessToken;
        $oAuth2Client = $fb->getOAuth2Client();
        $_SESSION['face_access_token'] = (string) $oAuth2Client->getLongLivedAccessToken($_SESSION['face_access_token']);
		    $fb->setDefaultAccessToken($_SESSION['face_access_token']);	
    }
    //Requisição das infos do usuário
    try{
        $response = $fb->get('/me?fields=name, email');
        $user = $response->getGraphUser();

        foreach($mysqli->query("SELECT cd_usuario AS codigo, nm_senha FROM tb_usuario WHERE nm_email = '". $user['email'] ."'") as $username){
            $codigo = $username['codigo'];
        }
        if($row == 1){
            $_SESSION['usuario'] = $codigo;
            header ('Location: painel.php');
            exit;
        }
        else{
            unset($_SESSION['face_access_token']);
            $_SESSION['nao_usuario'] = true;
            header ('Location: login.php');
            exit;
        }
    } 
    catch(Facebook\Exceptions\FacebookResponseException $e){
        echo $e->getMessage();
        exit;
    }
    catch(Facebook\Excepetions\FacebookSDKException $e){
        echo 'Ocorreu um erro no SDK do Facebook: ' . $e->getMessage();
        exit;
    }

}