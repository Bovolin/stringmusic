<?php
namespace Classes;

class ClassCarrinho{
    
    public function __construct(){
        session_start();
    }

    //Add produtos
    public function addProducts(ClassProdutos $product){
        if(isset($_SESSION['products']) && array_key_exists($product->getId(),$_SESSION['products'])){
            $_SESSION['products'][$product->getId()]['quantity']+=1;
        }else{
            $_SESSION['products'][$product->getId()]=[
                'id'=>$product->getId(),
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
                $html.="<td>" . $product['id'] . "</td>";
                $html.="<td>" . $product['description'] . "</td>";
                $html.="<td>" . (int)$product['quantity'] * $product['price'] . "</td>";
                $html.="<td>" . (int)$product['quantity'] . "</td>";
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