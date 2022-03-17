<?php
namespace Classes;

class ClassProdutos{

    private $id;
    private $description;
    private $price;

    public function __construct($id,$description, $price)
    {
        $this->setId($id);
        $this->setDescription($description);
        $this->setPrice($price);
    }

    public function getId(){return $this->id;}
    public function setId($id): void{$this->id = $id;}
    public function getDescription(){return $this->description;}
    public function setDescription($description): void{$this->description = $description;}
    public function getPrice(){return $this->price;}
    public function setPrice($price): void{$this->price = $price;}
}