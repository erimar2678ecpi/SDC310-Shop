<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

final class CatalogController extends Controller
{
    private $productModel;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->productModel = new Product($config);
    }

    public function index(): void
    {
        try {
            $cart = $_SESSION['cart'] ?? [];
            $products = $this->productModel->getAll();
            $this->view('catalog', [
                'products' => $products,
                'cart' => $cart,
                'title' => 'Product Catalog'
            ]);
        } catch (\Throwable $e) {
            error_log("Error in catalog: " . $e->getMessage());
            $this->view('catalog', [
                'products' => [],
                'cart' => [],
                'error' => 'Unable to load products'
            ]);
        }
    }
}
