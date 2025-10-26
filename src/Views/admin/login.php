<h2>Admin Login</h2>

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
        <?php foreach ($errors as $field => $fieldErrors): ?>
            <?php foreach ($fieldErrors as $error): ?>
                <p class="flash__message flash__message--error"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php $formAction = ($base_path ?? '') . '/admin/login'; ?>
<form method="post" action="<?= htmlspecialchars($formAction) ?>" class="form form--auth">
    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token) ?>">
    <div class="form__field">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
    </div>
    <div class="form__field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
    </div>
    <button type="submit" class="btn">Sign In</button>
</form>
