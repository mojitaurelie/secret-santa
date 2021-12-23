<?php
session_start();

if (isset($_SESSION["displayName"]) and isset($_SESSION["email"])) {
    header('Location: /dashboard');
    exit(0);
}

$_SESSION['CSRF'] = uniqid();
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$title = 'Inscription';
?>
<?php ob_start(); ?>
<form class="register-form" method="post" action="/account/signin.php">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?= $_SESSION['error'] ?>
        </div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="email" class="form-label">Adresse email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="displayName" class="form-label">Nom d'affichage</label>
        <input type="text" class="form-control" id="displayName" name="displayName" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="password-verification" class="form-label">Mot de passe (Confirmation)</label>
        <input type="password" class="form-control" id="password-verification" name="password-verification" required>
    </div>
    <input type="text" name="CSRF" value="<?= $_SESSION['CSRF'] ?>" style="display: none;" readonly>
    <button type="submit" class="btn btn-primary">Inscription</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php require($ROOT . '/template/default.php'); ?>
<?php unset($_SESSION['error']); ?>