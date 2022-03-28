<?php
require("../../lib/vendor/autoload.php");

$carrinho= new \Classes\ClassCarrinho();
if(isset($_POST['nm'])){
    $product = new \Classes\ClassProdutos($_POST['nm'],$_POST['price']);
    $_SESSION['quantity'] = $_POST['qt'];
    $carrinho->addProducts($product);
}

elseif($_GET['action'] == 'clear'){
    $carrinho->clearProducts();
}

header("Location: ../view/mercadopag.php")

?>