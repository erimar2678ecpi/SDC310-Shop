<h2>Catalog</h2>

<?php if (!empty($flash ?? [])): ?>
    <div class="flash" role="status" aria-live="polite">
        <?php foreach ($flash as $type => $messages): ?>
            <?php foreach ($messages as $message): ?>
                <p class="flash__message flash__message--<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($message) ?></p>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($error ?? '')): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (empty($products)): ?>
    <p>No products available.</p>
<?php else: ?>
    <div class="catalog">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <p class="product__id">ID: <?= htmlspecialchars((string)$product['id']) ?></p>
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p class="product__description"><?= htmlspecialchars($product['description'] ?? 'No description provided.') ?></p>
                <p class="product__price">$<?= number_format($product['price'], 2) ?></p>
                <?php $available = (int)$product['quantity']; ?>
                <?php if ($available === 0): ?>
                    <p class="product__inventory is-empty" aria-live="polite">Out of stock</p>
                <?php else: ?>
                    <p class="product__inventory">In stock: <?= htmlspecialchars((string)$available) ?></p>
                <?php endif; ?>
                <p class="product__incart">In cart: <?= $cart[$product['id']] ?? 0 ?></p>
                <div class="product__actions">
                    <form method="post" action="<?= htmlspecialchars(($base_path ?? '') . '/cart/add') ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <button type="submit" class="btn" <?= $available === 0 ? 'disabled aria-disabled="true"' : '' ?> aria-label="Add <?= htmlspecialchars($product['name']) ?> to cart">Add</button>
                    </form>
                    <form method="post" action="<?= htmlspecialchars(($base_path ?? '') . '/cart/remove') ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <button type="submit" class="btn remove" aria-label="Remove <?= htmlspecialchars($product['name']) ?> from cart">Remove</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (($pageCount ?? 1) > 1): ?>
        <nav class="pagination" aria-label="Catalog pages">
            <?php for ($i = 1; $i <= $pageCount; $i++): ?>
                <?php if ($i === ($page ?? 1)): ?>
                    <span class="pagination__link is-current">Page <?= $i ?></span>
                <?php else: ?>
                    <a class="pagination__link" href="?page=<?= $i ?>">Page <?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>
