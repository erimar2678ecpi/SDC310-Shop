<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Cart;

final class CatalogController extends Controller
{
    private Product $productModel;
    private Cart $cart;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->productModel = new Product($config);
        $this->cart = new Cart();
    }

    public function index(): void
    {
        try {
            $cart = $this->cart->all();
            $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
            $perPage = 6;
            $totalProducts = $this->productModel->countAll();
            $pageCount = max((int)ceil($totalProducts / $perPage), 1);
            $page = max(1, min($page, $pageCount));

            $products = $this->productModel->getPage($page, $perPage);
            $this->view('catalog', [
                'products' => $products,
                'cart' => $cart,
                'title' => 'Product Catalog',
                'page' => $page,
                'pageCount' => $pageCount,
                'totalProducts' => $totalProducts
            ]);
        } catch (\Throwable $e) {
            error_log("Error in catalog: " . $e->getMessage());
            $this->view('catalog', [
                'products' => [],
                'cart' => [],
                'error' => 'Unable to load products',
                'page' => 1,
                'pageCount' => 1,
                'totalProducts' => 0
            ]);
        }
    }
}
