<?php

/* Classe de Produtos */

/* Nota: essa classe serve como objeto para a Classe Carrinho;
    Todo produto adicionado é criado um novo objeto Produto e enviado para o objeto Carrinho. */

class ClassProdutos{

    private $description;
    private $price;
    private $code;
    
    //Método construtor do Produto
    public function __construct($description, $price, $code)
    {
        $this->setDescription($description);
        $this->setPrice($price);
        $this->setCode($code);
    }

    //"Getters" e "Setters" do Produto
    public function getDescription(){return $this->description;}
    public function setDescription($description): void{$this->description = $description;}
    public function getPrice(){return $this->price;}
    public function setPrice($price): void{$this->price = $price;}
    public function getCode(){return $this->code;}
    public function setCode($code): void{$this->code = $code;}
}