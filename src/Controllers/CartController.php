<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Validator;
use App\Models\Cart;
use App\Models\Product;

final class CartController extends Controller
{
    private Product $productModel;
    private Cart $cart;

    public function __construct(array $config)
    {
        parent::__construct($config);
        if (!isset($config['db'])) {
            throw new \RuntimeException('Database configuration is missing in config');
        }
        $this->productModel = new Product($config);
        $this->cart = new Cart();
    }

    public function viewCart(): void
    {
        $cartItems = $this->cart->all();
        $products = [];

        if (!empty($cartItems)) {
            $records = $this->productModel->getByIds(array_keys($cartItems));
            $recordMap = [];
            foreach ($records as $record) {
                $recordMap[$record['id']] = $record;
            }

            $adjustedForStock = false;
            foreach ($cartItems as $id => $quantity) {
                if (!isset($recordMap[$id])) {
                    $this->cart->remove((int)$id);
                    Flash::add('error', 'Removed an unavailable product from your cart.');
                    unset($cartItems[$id]);
                    continue;
                }
                $available = (int)$recordMap[$id]['quantity'];
                if ($quantity > $available) {
                    $cartItems[$id] = $available;
                    $this->cart->update((int)$id, $available, $available);
                    $adjustedForStock = true;
                    if ($available === 0) {
                        Flash::add('error', 'An item is now out of stock and was removed from your cart.');
                        unset($cartItems[$id]);
                        continue;
                    }
                }
                $products[] = $recordMap[$id];
            }

            if ($adjustedForStock) {
                Flash::add('info', 'Cart quantities were adjusted to match current stock.');
            }
        }

        $this->view('cart', [
            'cart' => $cartItems,
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
        $this->guardCsrf('/');
        $validator = new Validator();
        $id = $validator->int('id', $_POST['id'] ?? null, ['min' => 1]);

        if ($validator->hasErrors() || $id === null) {
            Flash::add('error', 'Invalid product selection.');
            $this->redirect($this->url('/'));
        }

        $product = $this->productModel->find($id);
        if ($product === null) {
            Flash::add('error', 'Product no longer exists.');
            $this->redirect($this->url('/'));
        }

        $added = $this->cart->increment($id, (int)$product['quantity']);
        if (!$added) {
            Flash::add('error', 'Cannot add more of this item; stock limit reached.');
        } else {
            Flash::add('success', 'Item added to your cart.');
        }

        $this->redirect($this->url('/'));
    }

    public function remove(): void
    {
        $this->guardCsrf('/cart');
        $validator = new Validator();
        $id = $validator->int('id', $_POST['id'] ?? null, ['min' => 1]);

        if ($validator->hasErrors() || $id === null) {
            Flash::add('error', 'Invalid product selection.');
            $this->redirect($this->url('/cart'));
        }

        if (!$this->cart->decrement($id)) {
            Flash::add('error', 'Item was not in your cart.');
        }

        $this->redirect($this->url('/cart'));
    }

    public function update(): void
    {
        $this->guardCsrf('/cart');
        $validator = new Validator();
        $id = $validator->int('id', $_POST['id'] ?? null, ['min' => 1]);
        $quantity = $validator->int('quantity', $_POST['quantity'] ?? null, ['min' => 0]);

        if ($validator->hasErrors() || $id === null || $quantity === null) {
            Flash::add('error', 'Invalid cart update request.');
            $this->redirect($this->url('/cart'));
        }

        $product = $this->productModel->find($id);
        if ($product === null) {
            $this->cart->remove($id);
            Flash::add('error', 'Product no longer exists and was removed from your cart.');
            $this->redirect($this->url('/cart'));
        }

        $maxAvailable = (int)$product['quantity'];
        $adjustedQuantity = max(0, min($quantity, $maxAvailable));
        $changed = $this->cart->update($id, $quantity, $maxAvailable);

        if (!$changed) {
            Flash::add('info', 'No changes were made to your cart.');
        } elseif ($adjustedQuantity !== $quantity) {
            Flash::add('info', 'Quantity adjusted to available stock.');
        } else {
            Flash::add('success', 'Cart updated.');
        }

        $this->redirect($this->url('/cart'));
    }

    public function checkout(): void
    {
        $this->guardCsrf('/cart');
        $this->cart->reset();
        \App\Core\Flash::add('info', 'Order completed. Cart cleared.');
        $base = $this->config['base_path'] ?? '';
        header('Location: ' . ($base === '' ? '/' : $base . '/'));
        exit;
    }
}