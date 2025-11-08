<h2>Your Cart</h2>
<?php if (!empty($flash ?? [])): ?>
    <div class="flash" role="status" aria-live="polite">
        <?php foreach ($flash as $type => $messages): ?>
            <?php foreach ($messages as $message): ?>
                <p class="flash__message flash__message--<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($message) ?></p>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php if (empty($cart)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table class="catalog-table">
        <caption class="sr-only">Items currently in your cart</caption>
        <thead>
            <tr>
                <th scope="col">Product</th>
                <th scope="col">Description</th>
                <th scope="col">Price</th>
                <th scope="col">Available</th>
                <th scope="col">Quantity</th>
                <th scope="col">Total</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product):
            $quantity = $cart[$product['id']] ?? 0;
            $price = $product['cost'] ?? $product['price'];
            $itemTotal = $quantity * $price;
        ?>
            <tr>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td>$<?= number_format($price, 2) ?></td>
                <td><?= htmlspecialchars((string)$product['quantity']) ?></td>
                <td><?= $quantity ?></td>
                <td>$<?= number_format($itemTotal, 2) ?></td>
                <td class="actions">
                    <div class="actions__group">
                        <form method="post" action="<?= htmlspecialchars(($base_path ?? '') . '/cart/add') ?>">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
                            <button type="submit" class="btn" aria-label="Add one <?= htmlspecialchars($product['name']) ?>">+</button>
                        </form>
                        <form method="post" action="<?= htmlspecialchars(($base_path ?? '') . '/cart/remove') ?>">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
                            <button type="submit" class="btn remove" aria-label="Remove one <?= htmlspecialchars($product['name']) ?>">-</button>
                        </form>
                    </div>
                    <form method="post" action="<?= htmlspecialchars(($base_path ?? '') . '/cart/update') ?>" class="actions__update">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <label class="sr-only" for="quantity-<?= htmlspecialchars($product['id']) ?>">Quantity for <?= htmlspecialchars($product['name']) ?></label>
                        <input type="number" name="quantity" id="quantity-<?= htmlspecialchars($product['id']) ?>" min="0" max="<?= htmlspecialchars((string)$product['quantity']) ?>" value="<?= $quantity ?>" class="input--quantity">
                        <button type="submit" class="btn">Update</button>
                    </form>
                    <form method="post" action="<?= htmlspecialchars(($base_path ?? '') . '/cart/update') ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                        <input type="hidden" name="quantity" value="0">
                        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <button type="submit" class="btn remove">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right">Subtotal:</td>
                <td colspan="2">$<?= number_format($summary['subtotal'], 2) ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right">Shipping (<?= $summary['ship_rate'] * 100 ?>%):</td>
                <td colspan="2">$<?= number_format($summary['shipping'], 2) ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right">Tax (<?= $summary['tax_rate'] * 100 ?>%):</td>
                <td colspan="2">$<?= number_format($summary['tax'], 2) ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right"><strong>Grand Total:</strong></td>
                <td colspan="2"><strong>$<?= number_format($summary['grand_total'], 2) ?></strong></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2" style="text-align: right">
                <a class="btn" href="<?= htmlspecialchars(($base_path ?? '') . '/cart/checkout') ?>">Checkout</a>
            </td>
        </tr>
        </tfoot>
    </table>
<?php endif; ?>
