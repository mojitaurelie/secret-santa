<?php
session_start();

if (isset($_SESSION["displayName"]) and isset($_SESSION["email"])) {
    header('Location: /dashboard');
    exit(0);
}

$_SESSION['CSRF'] = uniqid();
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$title = 'Bienvenue';
?>
<?php ob_start(); ?>
<form class="login-form" method="post" action="/account/login.php">
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
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <input type="text" name="CSRF" value="<?= $_SESSION['CSRF'] ?>" style="display: none;" readonly>
    <a class="btn btn-outline-dark" href="signup.php">Inscription</a>
    <button type="submit" class="btn btn-primary">Connexion</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php require($ROOT . '/template/default.php'); ?>
<?php unset($_SESSION['error']); ?>