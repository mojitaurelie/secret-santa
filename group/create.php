<?php
session_start();

if (!isset($_SESSION["displayName"]) or !isset($_SESSION["email"])) {
    $_SESSION["error"] = "Votre session a expiré (ERR_SESSION_EXPIRED)";
    header('Location: /');
    exit(0);
}

$_SESSION['CSRF'] = uniqid();
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$title = "Créer un groupe";
?>
<?php ob_start(); ?>
<form class="login-form" method="post" action="/group/create-process.php">
    <div class="mb-3">
        <label for="name" class="form-label">Nom</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <input type="text" name="CSRF" value="<?= $_SESSION['CSRF'] ?>" style="display: none;" readonly>
    <button type="submit" class="btn btn-primary">Créer</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php require($ROOT . '/template/default.php'); ?>
