<?php
namespace Classes;

class ClassProdutos{

    private $description;
    private $price;

    public function __construct($description, $price)
    {
        $this->setDescription($description);
        $this->setPrice($price);
    }
    public function getDescription(){return $this->description;}
    public function setDescription($description): void{$this->description = $description;}
    public function getPrice(){return $this->price;}
    public function setPrice($price): void{$this->price = $price;}
}