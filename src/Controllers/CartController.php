<?php
namespace App\Controllers;

use App\Core\Controller;

final class CartController extends Controller
{
    public function view(): void
    {
        $cart = $_SESSION['cart'] ?? [];
        $this->view('cart', ['cart' => $cart]);
    }

    public function add(): void
    {
        $id = $_POST['id'] ?? null;
        if ($id === null) { $this->redirect('/'); }
        $_SESSION['cart'][] = $id;
        $this->redirect('/cart');
    }
}
