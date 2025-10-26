<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Validator;
use App\Models\User;

final class AuthController extends Controller
{
    private User $userModel;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->userModel = new User($config);
    }

    public function showLogin(): void
    {
        if (!empty($_SESSION['is_admin'])) {
            $this->redirect($this->url('/admin/products'));
        }

        $this->renderLogin();
    }

    public function login(): void
    {
        $this->guardCsrf('/admin/login');
        $validator = new Validator();
        $email = $validator->email('email', $_POST['email'] ?? null);
        $password = $_POST['password'] ?? '';

        if ($password === '') {
            $validator->addError('password', 'Password is required.');
        }

        if ($validator->hasErrors()) {
            $this->renderLogin([
                'errors' => $validator->errors(),
                'old' => ['email' => $_POST['email'] ?? '']
            ]);
            return;
        }

        $user = $this->userModel->findByEmail($email ?? '');

        if (!$user || !password_verify($password, $user['password']) || empty($user['is_admin'])) {
            Flash::add('error', 'Invalid credentials or insufficient permissions.');
            $this->renderLogin([
                'old' => ['email' => $email]
            ]);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = (bool) $user['is_admin'];
        $_SESSION['user_name'] = $user['name'] ?: $user['email'];

        Flash::add('success', 'Welcome back, ' . ($_SESSION['user_name'] ?? 'admin') . '!');
        $this->redirect($this->url('/admin/products'));
    }

    public function logout(): void
    {
        $this->guardCsrf('/');
        unset($_SESSION['user_id'], $_SESSION['is_admin'], $_SESSION['user_name']);
        session_regenerate_id(true);
        Flash::add('success', 'You have been logged out.');
        $this->redirect($this->url('/admin/login'));
    }

    private function renderLogin(array $data = []): void
    {
        $defaults = [
            'title' => 'Admin Login',
            'errors' => [],
            'old' => ['email' => '']
        ];

        $this->view('admin/login', array_merge($defaults, $data));
    }
}
