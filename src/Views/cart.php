<h2>Your Cart</h2>
<?php if (empty($cart)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table class="catalog-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Description</th>
                <th>Price</th>
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
                <td><?= $quantity ?></td>
                <td>$<?= number_format($itemTotal, 2) ?></td>
                <td class="actions">
                    <form method="post" action="/Week2Shop/public/cart/add" style="display:inline">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                        <button type="submit" class="btn">+</button>
                    </form>
                    <form method="post" action="/Week2Shop/public/cart/remove" style="display:inline">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                        <button type="submit" class="btn remove">-</button>
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
        </tfoot>
    </table>
<?php endif; ?>
