<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $title ?? 'SDC310 Shop' ?></title>
    <?php $basePath = isset($base_path) ? rtrim($base_path, '/') : ''; ?>
    <link rel="stylesheet" href="<?= htmlspecialchars(($basePath === '' ? '' : $basePath) . '/assets/css/style.css') ?>">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; margin: 0; }
        header, footer { background: #222; color: #fff; padding: 1em 2em; }
        nav { background: #eee; padding: 0.5em 2em; margin-bottom: 1em; }
        nav a, nav form { color: #222; text-decoration: none; margin-right: 1em; font-weight: bold; display: inline-block; }
        nav form { margin: 0; }
        nav button { background: none; border: none; color: #222; font-weight: bold; cursor: pointer; padding: 0; }
        main { padding: 2em; }
        .catalog-table { width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 2em; }
        .catalog-table th, .catalog-table td { border: 1px solid #ccc; padding: 0.5em 1em; text-align: left; }
        .catalog-table th { background: #f0f0f0; }
        .catalog-table td.actions { text-align: center; }
        .btn { padding: 0.3em 0.8em; border: none; background: #007bff; color: #fff; border-radius: 3px; cursor: pointer; font-size: 1em; }
        .btn.remove { background: #dc3545; }
    </style>
</head>
<body>
    <header>
        <h1><?= $title ?? 'SDC310 Shop' ?></h1>
    </header>
    <nav>
        <?php 
            $homeUrl = $basePath === '' ? '/' : $basePath . '/';
            $cartUrl = $basePath === '' ? '/cart' : $basePath . '/cart';
            $adminUrl = $basePath === '' ? '/admin' : $basePath . '/admin';
        ?>
        <a href="<?= htmlspecialchars($homeUrl) ?>">Catalog</a>
        <a href="<?= htmlspecialchars($cartUrl) ?>">Cart</a>
        <?php if (!empty($auth['is_admin'])): ?>
            <a href="<?= htmlspecialchars($adminUrl . '/products') ?>">Manage Products</a>
            <form method="post" action="<?= htmlspecialchars($adminUrl . '/logout') ?>" style="display:inline">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <button type="submit">Logout<?= !empty($auth['user_name']) ? ' (' . htmlspecialchars($auth['user_name']) . ')' : '' ?></button>
            </form>
        <?php else: ?>
            <a href="<?= htmlspecialchars($adminUrl . '/login') ?>">Admin Login</a>
        <?php endif; ?>
    </nav>
    <main>
        <?php require $templatePath; ?>
    </main>
    <footer>
        <small>&copy; <?= date('Y') ?> SDC310 Shop</small>
    </footer>
</body>
</html>
