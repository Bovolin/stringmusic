<?php
class ClassProdutos{

    private $description;
    private $price;
    private $code;
    
    public function __construct($description, $price, $code)
    {
        $this->setDescription($description);
        $this->setPrice($price);
        $this->setCode($code);
    }
    public function getDescription(){return $this->description;}
    public function setDescription($description): void{$this->description = $description;}
    public function getPrice(){return $this->price;}
    public function setPrice($price): void{$this->price = $price;}
    public function getCode(){return $this->code;}
    public function setCode($code): void{$this->code = $code;}
}