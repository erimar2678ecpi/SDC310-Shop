<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

final class CartController extends Controller
{
    private $productModel;

    public function __construct(array $config)
    {
        parent::__construct($config);
        if (!isset($config['db'])) {
            throw new \RuntimeException('Database configuration is missing in config');
        }
        $this->productModel = new Product($config);
    }

    public function viewCart(): void
    {
        $cart = $_SESSION['cart'] ?? [];
        $products = [];
        
        if (!empty($cart)) {
            $products = $this->productModel->getByIds(array_keys($cart));
        }
        
        $this->view('cart', [
            'cart' => $cart,
            'products' => $products,
            'title' => 'Your Cart',
            'config' => [
                'tax_rate' => $this->config['tax_rate'] ?? 0.05,
                'ship_rate' => $this->config['ship_rate'] ?? 0.10
            ]
        ]);
    }

    public function add(): void
    {
        $id = $_POST['id'] ?? null;
        if ($id === null) {
            $this->redirect('/Week2Shop/public/');
        }
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = 0;
        }
        $_SESSION['cart'][$id]++;
        $this->redirect('/Week2Shop/public/cart');
    }

    public function remove(): void
    {
        $id = $_POST['id'] ?? null;
        if ($id === null || !isset($_SESSION['cart'][$id])) {
            $this->redirect('/Week2Shop/public/cart');
        }
        $_SESSION['cart'][$id]--;
        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
        $this->redirect('/Week2Shop/public/cart');
    }
}
