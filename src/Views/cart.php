<h2>Your Cart</h2>
<?php if (!empty($flash ?? [])): ?>
    <div class="flash">
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
        <thead>
            <tr>
                <th>Product</th>
                <th>Description</th>
                <th>Price</th>
                <th>Available</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $total = 0;
        foreach ($products as $product):
            $quantity = $cart[$product['id']] ?? 0;
            $itemTotal = $quantity * ($product['cost'] ?? $product['price']);
            $total += $itemTotal;
        ?>
            <tr>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td>$<?= number_format($product['cost'] ?? $product['price'], 2) ?></td>
                <td><?= htmlspecialchars((string)$product['quantity']) ?></td>
                <td><?= $quantity ?></td>
                <td>$<?= number_format($itemTotal, 2) ?></td>
                <td class="actions">
                    <div class="actions__group">
                        <form method="post" action="<?= htmlspecialchars(($base_path ?? '') . '/cart/add') ?>">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
                            <button type="submit" class="btn">+</button>
                        </form>
                        <form method="post" action="<?= htmlspecialchars(($base_path ?? '') . '/cart/remove') ?>">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
                            <button type="submit" class="btn remove">-</button>
                        </form>
                    </div>
                    <form method="post" action="<?= htmlspecialchars(($base_path ?? '') . '/cart/update') ?>" class="actions__update">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <label class="sr-only" for="quantity-<?= htmlspecialchars($product['id']) ?>">Quantity</label>
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
                <td colspan="2">$<?= number_format($total, 2) ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right">Shipping (<?= ($config['ship_rate'] * 100) ?>%):</td>
                <td colspan="2">$<?= number_format($shipping = $total * $config['ship_rate'], 2) ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right">Tax (<?= ($config['tax_rate'] * 100) ?>%):</td>
                <td colspan="2">$<?= number_format($tax = ($total + $shipping) * $config['tax_rate'], 2) ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right"><strong>Grand Total:</strong></td>
                <td colspan="2"><strong>$<?= number_format($total + $shipping + $tax, 2) ?></strong></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2" style="text-align: right">
                <form method="post" action="<?= htmlspecialchars(($base_path ?? '') . '/cart/checkout') ?>">
                    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <button type="submit" class="btn">Checkout</button>
                </form>
            </td>
        </tr>
        </tfoot>
    </table>
<?php endif; ?>
