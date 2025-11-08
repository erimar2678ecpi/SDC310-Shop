<h2><?= htmlspecialchars($title ?? 'Confirm Your Order') ?></h2>

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
    <p>Your cart is empty. <a href="<?= htmlspecialchars(($base_path ?? '') . '/') ?>">Return to the catalog</a>.</p>
<?php else: ?>
    <p>Please review the items below before placing your order.</p>
    <table class="catalog-table">
        <caption class="sr-only">Order summary prior to checkout</caption>
        <thead>
            <tr>
                <th>Product</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Line Total</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <?php
                $quantity = $cart[$product['id']] ?? 0;
                $price = $product['cost'] ?? $product['price'];
                $lineTotal = $quantity * $price;
            ?>
            <tr>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td>$<?= number_format($price, 2) ?></td>
                <td><?= $quantity ?></td>
                <td>$<?= number_format($lineTotal, 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;">Subtotal:</td>
                <td>$<?= number_format($summary['subtotal'], 2) ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right;">Shipping (<?= $summary['ship_rate'] * 100 ?>%):</td>
                <td>$<?= number_format($summary['shipping'], 2) ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right;">Tax (<?= $summary['tax_rate'] * 100 ?>%):</td>
                <td>$<?= number_format($summary['tax'], 2) ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Grand Total:</strong></td>
                <td><strong>$<?= number_format($summary['grand_total'], 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <form method="post" action="<?= htmlspecialchars(($base_path ?? '') . '/cart/checkout') ?>" class="checkout-confirm">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
        <button type="submit" class="btn">Confirm Purchase</button>
        <a class="btn secondary" href="<?= htmlspecialchars(($base_path ?? '') . '/cart') ?>">Return to Cart</a>
    </form>
<?php endif; ?>
