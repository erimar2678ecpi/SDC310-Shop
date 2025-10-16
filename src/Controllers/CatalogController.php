<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

final class CatalogController extends Controller
{
    public function index(): void
    {
        try {
            $db = new Database($this->config);
            $products = $db->allProducts();
        } catch (\Throwable $e) {
            // fallback to static list if DB is unavailable
            $products = [
                ['id'=>1,'name'=>'T-shirt','price'=>19.99],
                ['id'=>2,'name'=>'Mug','price'=>9.5],
                ['id'=>3,'name'=>'Sticker','price'=>2.0],
            ];
        }

        $this->view('catalog', ['products' => $products]);
    }
}
