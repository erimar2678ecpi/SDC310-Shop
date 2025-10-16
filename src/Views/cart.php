<h2>Your Cart</h2>
<?php if (empty($cart)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <ul>
    <?php foreach ($cart as $item): ?>
        <li>Product ID: <?= htmlspecialchars($item) ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
