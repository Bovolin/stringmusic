<?php
namespace Classes;

class ClassCarrinho{
    
    public function __construct(){
        session_start();
    }

    //Add produtos
    public function addProducts(ClassProdutos $product){
        if(isset($_SESSION['products']) && array_key_exists($product->getDescription(),$_SESSION['products'])){
            $_SESSION['products'][$product->getDescription()]['quantity']+=1;
        }
        else{
            $_SESSION['products'][$product->getDescription()]=[
                'description'=>$product->getDescription(),
                'price'=>$product->getPrice(),
                'quantity'=>1
            ];
        }
    }

    //Limpar produtos
    public function clearProducts(){
        unset($_SESSION['products']);
    }

    //Contar produtos
    public function getQuantity(){
        $quantity = 0;
        if(isset($_SESSION['products'])){
            foreach($_SESSION['products'] as $product){
                $quantity += $product['quantity'];
            }
        }
        return $quantity;
    }

    //Listar produtos
    public function listProducts(){
        $html="";
        if(isset($_SESSION['products'])){
            foreach($_SESSION['products'] as $product){
                $html.="<tr>";
                $html.="<td>";
                $html.="<br> <strong><span class='thin'>" . $product['description'] . "</span></strong>";
                $html.="<br> <span class='thin small'> Quantidade: " . $product['quantity'] . "<br><br></span>";
                $html.="</td>";
                $html.="</tr>";
                $html.="<tr>";
                $html.="<td>";
                $html.="<div class='price'>R$" . $product['price'] . "</div>";
                $html.="</td>";
                $html.="</tr>";
            } 
        }
        
        return $html;
    }

    //Amount produtos
    public function getAmount(){
        $amount = 0;
        if(isset($_SESSION['products'])){
           foreach($_SESSION['products'] as $product){
                $amount += $product['quantity'] * $product['price'];
            } 
        }
        return $amount;
    }
}

?>