<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Validator;
use App\Models\Category;
use App\Models\Product;

final class ProductController extends Controller
{
    private Product $productModel;
    private Category $categoryModel;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->productModel = new Product($config);
        $this->categoryModel = new Category($config);
    }

    public function index(): void
    {
        $this->requireAdmin();

        $this->view('admin/products/index', [
            'title' => 'Manage Products',
            'products' => $this->productModel->getAll()
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $this->view('admin/products/create', [
            'title' => 'Create Product',
            'categories' => $this->categoryModel->all(),
            'errors' => [],
            'old' => []
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->guardCsrf('/admin/products/create');

        $validator = new Validator();
        $categoryId = $validator->int('category_id', $_POST['category_id'] ?? null, ['required' => false, 'min' => 1]);
        $name = $validator->string('name', $_POST['name'] ?? null, ['min' => 3, 'max' => 200]);
        $description = $validator->string('description', $_POST['description'] ?? null, ['required' => false, 'max' => 1000]);
        $price = $validator->float('price', $_POST['price'] ?? null, ['min' => 0]);
        $quantity = $validator->int('quantity', $_POST['quantity'] ?? null, ['min' => 0]);

        if ($validator->hasErrors()) {
            $this->view('admin/products/create', [
                'title' => 'Create Product',
                'categories' => $this->categoryModel->all(),
                'errors' => $validator->errors(),
                'old' => $_POST
            ]);
            return;
        }

        $this->productModel->create([
            'category_id' => $categoryId,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'quantity' => $quantity
        ]);

        Flash::add('success', 'Product created successfully.');
        $this->redirect($this->adminUrl('/products'));
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::add('error', 'Invalid product selection.');
            $this->redirect($this->adminUrl('/products'));
        }

        $product = $this->productModel->find($id);
        if ($product === null) {
            Flash::add('error', 'Product not found.');
            $this->redirect($this->adminUrl('/products'));
        }

        $this->view('admin/products/edit', [
            'title' => 'Edit Product',
            'product' => $product,
            'categories' => $this->categoryModel->all(),
            'errors' => []
        ]);
    }

    public function update(): void
    {
        $this->requireAdmin();
        $this->guardCsrf('/admin/products');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::add('error', 'Invalid product update request.');
            $this->redirect($this->adminUrl('/products'));
        }

        $product = $this->productModel->find($id);
        if ($product === null) {
            Flash::add('error', 'Product not found.');
            $this->redirect($this->adminUrl('/products'));
        }

        $validator = new Validator();
        $categoryId = $validator->int('category_id', $_POST['category_id'] ?? null, ['required' => false, 'min' => 1]);
        $name = $validator->string('name', $_POST['name'] ?? null, ['min' => 3, 'max' => 200]);
        $description = $validator->string('description', $_POST['description'] ?? null, ['required' => false, 'max' => 1000]);
        $price = $validator->float('price', $_POST['price'] ?? null, ['min' => 0]);
        $quantity = $validator->int('quantity', $_POST['quantity'] ?? null, ['min' => 0]);

        if ($validator->hasErrors()) {
            $this->view('admin/products/edit', [
                'title' => 'Edit Product',
                'product' => array_merge($product, $_POST),
                'categories' => $this->categoryModel->all(),
                'errors' => $validator->errors()
            ]);
            return;
        }

        $this->productModel->update($id, [
            'category_id' => $categoryId,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'quantity' => $quantity
        ]);

        Flash::add('success', 'Product updated successfully.');
        $this->redirect($this->adminUrl('/products'));
    }

    public function destroy(): void
    {
        $this->requireAdmin();
        $this->guardCsrf('/admin/products');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::add('error', 'Invalid delete request.');
            $this->redirect($this->adminUrl('/products'));
        }

        $this->productModel->delete($id);
        Flash::add('success', 'Product removed.');
        $this->redirect($this->adminUrl('/products'));
    }

    private function requireAdmin(): void
    {
        if (empty($_SESSION['is_admin'])) {
            Flash::add('error', 'Please log in as an administrator.');
            $this->redirect($this->url('/admin/login'));
        }
    }

    private function adminUrl(string $path): string
    {
        return $this->url('/admin' . $path);
    }
}
