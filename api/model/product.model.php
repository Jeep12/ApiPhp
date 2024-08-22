<?php
require_once("model.php");

class ProductsModel extends Model
{
    public function getAllProducts()
    {
        $sql = "SELECT * FROM comidas";
        $query = $this->pdo->prepare($sql);
        $query->execute();
        $products = $query->fetchAll(PDO::FETCH_OBJ);
        return $products;
    }
}
