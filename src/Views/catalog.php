<h2>Catalog</h2>
<?php if (empty($products)): ?>
    <p>No products available.</p>
<?php else: ?>
    <table class="catalog-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Cost</th>
                <th>In Cart</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['id']) ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['description'] ?? '') ?></td>
                <td>$<?= number_format($p['cost'] ?? $p['price'], 2) ?></td>
                <td><?= isset($cart[$p['id']]) ? $cart[$p['id']] : 0 ?></td>
                <td class="actions">
                    <form method="post" action="/Week2Shop/public/cart/add" style="display:inline">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($p['id']) ?>">
                        <button type="submit" class="btn">Add</button>
                    </form>
                    <form method="post" action="/Week2Shop/public/cart/remove" style="display:inline">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($p['id']) ?>">
                        <button type="submit" class="btn remove">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
