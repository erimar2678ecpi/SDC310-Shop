<h2>Create Product</h2>

<?php if (!empty($flash ?? [])): ?>
    <div class="flash">
        <?php foreach ($flash as $type => $messages): ?>
            <?php foreach ($messages as $message): ?>
                <p class="flash__message flash__message--<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($message) ?></p>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($errors ?? [])): ?>
    <div class="flash">
        <?php foreach ($errors as $fieldErrors): ?>
            <?php foreach ($fieldErrors as $error): ?>
                <p class="flash__message flash__message--error"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php $actionUrl = ($base_path ?? '') . '/admin/products'; ?>
<form method="post" action="<?= htmlspecialchars($actionUrl) ?>" class="form">
    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
    <div class="form__field">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
    </div>
    <div class="form__field">
        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
    </div>
    <div class="form__field">
        <label for="category_id">Category</label>
        <select name="category_id" id="category_id">
            <option value="">(None)</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category['id']) ?>" <?= isset($old['category_id']) && (int)$old['category_id'] === (int)$category['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form__field">
        <label for="price">Price</label>
        <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($old['price'] ?? '') ?>" required>
    </div>
    <div class="form__field">
        <label for="quantity">Quantity</label>
        <input type="number" name="quantity" id="quantity" min="0" value="<?= htmlspecialchars($old['quantity'] ?? '') ?>" required>
    </div>
    <button type="submit" class="btn">Create</button>
</form>
