<?php
require_once("api/model/product.model.php");
require_once("api/view/api-view.php");
require_once("api/model/user.model.php");

class ProductController
{
    private $modelProducts;
    private $view;  
    private $model;

    public function __construct()
    {
        $this->modelProducts = new ProductsModel();
        $this->model = new Model();

        $this->view = new APIView();
    }

    public function getAllProducts()
    {
        $customHeader = $_SERVER['HTTP_X_CUSTOM_HEADER'] ?? null;
        $jwt = $customHeader ? str_replace('Bearer ', '', $customHeader) : null;

        if ($jwt && $this->model->validateJWT($jwt)) {
            $products = $this->modelProducts->getAllProducts();
            $this->view->response($products, 200);
        } else {
            $this->view->response("Unauthorized", 401);
        }
    }
}
?>
