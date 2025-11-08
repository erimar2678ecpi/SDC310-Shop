<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $title ?? 'SDC310 Shop' ?></title>
    <?php $basePath = isset($base_path) ? rtrim($base_path, '/') : ''; ?>
    <link rel="stylesheet" href="<?= htmlspecialchars(($basePath === '' ? '' : $basePath) . '/assets/css/style.css') ?>">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; margin: 0; color: #111; }
        header, footer { background: #222; color: #fff; padding: 1em 2em; }
        nav { background: #e4e4e4; padding: 0.5em 2em; margin-bottom: 1em; }
        nav a, nav form { color: #111; text-decoration: none; margin-right: 1em; font-weight: bold; display: inline-block; }
        nav form { margin: 0; }
        nav button { background: none; border: none; color: #111; font-weight: bold; cursor: pointer; padding: 0; }
        nav a:focus-visible,
        nav button:focus-visible,
        .btn:focus-visible,
        .skip-link:focus-visible { outline: 3px solid #ffbf47; outline-offset: 2px; }
        main { padding: 2em; }
        .catalog-table { width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 2em; }
        .catalog-table th, .catalog-table td { border: 1px solid #b3b3b3; padding: 0.5em 1em; text-align: left; }
        .catalog-table th { background: #f0f0f0; }
        .catalog-table td.actions { text-align: center; }
        .btn { padding: 0.3em 0.8em; border: none; background: #005bbb; color: #fff; border-radius: 3px; cursor: pointer; font-size: 1em; }
        .btn.secondary { background: #6c757d; }
        .btn.remove { background: #b00020; }
        .skip-link { position: absolute; left: -999px; top: auto; width: 1px; height: 1px; overflow: hidden; }
        .skip-link:focus { position: static; width: auto; height: auto; margin: 1em 2em; }
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }
    </style>
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header role="banner">
        <h1><?= $title ?? 'SDC310 Shop' ?></h1>
    </header>
    <nav role="navigation" aria-label="Primary site links">
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
    <main id="main-content" tabindex="-1">
        <?php require $templatePath; ?>
    </main>
    <footer role="contentinfo">
        <small>&copy; <?= date('Y') ?> SDC310 Shop</small>
    </footer>
</body>
</html>
