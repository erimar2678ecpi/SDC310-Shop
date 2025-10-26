<h2>Products</h2>

<?php if (!empty($flash ?? [])): ?>
    <div class="flash">
        <?php foreach ($flash as $type => $messages): ?>
            <?php foreach ($messages as $message): ?>
                <p class="flash__message flash__message--<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($message) ?></p>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php $createUrl = ($base_path ?? '') . '/admin/products/create'; ?>
<p>
    <a class="btn" href="<?= htmlspecialchars($createUrl) ?>">Add Product</a>
</p>

<?php if (empty($products)): ?>
    <p>No products found.</p>
<?php else: ?>
    <table class="catalog-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['id']) ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>$<?= number_format($product['price'], 2) ?></td>
                <td><?= htmlspecialchars((string)$product['quantity']) ?></td>
                <td>
                    <div class="table-actions">
                        <a class="btn" href="<?= htmlspecialchars(($base_path ?? '') . '/admin/products/edit?id=' . urlencode($product['id'])) ?>">Edit</a>
                        <form method="post" action="<?= htmlspecialchars(($base_path ?? '') . '/admin/products/delete') ?>" onsubmit="return confirm('Delete this product?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
                            <button type="submit" class="btn remove">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
