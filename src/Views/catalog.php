<h2>Catalog</h2>
<?php if (empty($products)): ?>
    <p>No products available.</p>
<?php else: ?>
    <div class="catalog">
    <?php foreach ($products as $p): ?>
        <div class="product">
            <strong><?= htmlspecialchars($p['name']) ?></strong>
            <div>$<?= number_format($p['price'],2) ?></div>
            <form method="post" action="/cart/add">
                <input type="hidden" name="id" value="<?= htmlspecialchars($p['id']) ?>">
                <button type="submit">Add to cart</button>
            </form>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
